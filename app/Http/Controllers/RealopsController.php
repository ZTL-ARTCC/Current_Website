<?php

namespace App\Http\Controllers;

use App\Importers\RealopsFlightImporter;
use App\RealopsFlight;
use App\RealopsPilot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Throwable;

class RealopsController extends Controller {
    public function index(Request $request) {
        $airport_filter = $request->get('filter');
        $flights = RealopsFlight::where('dep_airport', 'like', '%' . $airport_filter . '%')
                                ->orWhere('arr_airport', 'like', '%' . $airport_filter . '%')
                                ->orderBy('flight_date', 'ASC')
                                ->orderBy('dep_time', 'ASC')
                                ->paginate(20);

        return view('site.realops')->with('flights', $flights)->with('airport_filter', $airport_filter);
    }

    public function bid($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'That flight doesn\'t exist');
        }
        
        $pilot = auth()->guard('realops')->user();
        $flight->assignPilotToFlight($pilot->id);

        Mail::send('emails.realops.bid', ['flight' => $flight, 'pilot' => $pilot], function ($message) use ($pilot, $flight) {
            $message->from('realops@notams.ztlartcc.org', 'ZTL Realops')->subject($this->emailSubIntro($flight->flight_number) . 'Bid Confirmation');
            $message->to($pilot->email);
        });

        return redirect()->back()->with('success', 'You have bid for that flight successfully. You should receive a confirmation email soon and will receive email updates regarding your flight');
    }

    public function cancelBid() {
        $pilot = auth()->guard('realops')->user();
        $flight = $pilot->assigned_flight;

        if (! $flight) {
            return redirect()->back()->with('error', 'You have not yet bid for a flight');
        }
        
        $flight->removeAssignedPilot();

        Mail::send('emails.realops.cancel_bid', ['flight' => $flight, 'pilot' => $pilot], function ($message) use ($pilot, $flight) {
            $message->from('realops@notams.ztlartcc.org', 'ZTL Realops')->subject($this->emailSubIntro($flight->flight_number) . 'Bid Cancelled');
            $message->to($pilot->email);
        });

        return redirect()->back()->with('success', 'You have removed your bid successfully');
    }

    public function adminIndex() {
        $flights = RealopsFlight::orderBy('flight_date', 'ASC')->orderBy('dep_time', 'ASC')->paginate(20);
        $all_pilots = RealopsPilot::orderBy('lname', 'ASC')->get();
        $pilots = [];

        foreach ($all_pilots as $pilot) {
            $pilots[$pilot->id] = $pilot->full_name;
        }

        return view('dashboard.admin.realops.index')->with('flights', $flights)->with('pilots', $pilots);
    }

    public function assignPilotToFlight(Request $request, $id) {
        $request->validate([
            'pilot' => 'required|exists:realops_pilots,id'
        ]);

        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'Unable to assign the pilot to that flight');
        }

        $pilot = RealopsPilot::find($request->input('pilot'));
        $flight->assignPilotToFlight($pilot->id);

        Mail::send('emails.realops.assigned_flight', ['flight' => $flight, 'pilot' => $pilot], function ($message) use ($pilot, $flight) {
            $message->from('realops@notams.ztlartcc.org', 'ZTL Realops')->subject($this->emailSubIntro($flight->flight_number) . 'Assignment Confirmation');
            $message->to($pilot->email);
        });

        return redirect()->back()->with('success', 'That pilot was assigned successfully');
    }

    public function removePilotFromFlight($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'Unable to remove the assigned pilot from that flight');
        }

        $pilot = $flight->assigned_pilot;
        $flight->removeAssignedPilot();

        Mail::send('emails.realops.removed_from_flight', ['flight' => $flight, 'pilot' => $pilot], function ($message) use ($pilot, $flight) {
            $message->from('realops@notams.ztlartcc.org', 'ZTL Realops')->subject($this->emailSubIntro($flight->flight_number) . 'Unassignment Confirmation');
            $message->to($pilot->email);
        });

        return redirect()->back()->with('success', 'Pilot unassigned successfully');
    }

    public function showCreateFlight() {
        return view('dashboard.admin.realops.create');
    }
     
    public function createFlight(Request $request) {
        $request->validate([
            'flight_number' => 'required',
            'flight_date' => 'required|date_format:m/d/Y',
            'dep_time' => 'required|date_format:H:i',
            'dep_airport' => 'required',
            'arr_airport' => 'required',
            'est_arr_time' => 'date_format:H:i|nullable',
        ]);

        $flight = new RealopsFlight;
        $flight->flight_number = $request->input('flight_number');
        $flight->flight_date = Carbon::parse($request->input('flight_date'))->format('Y-m-d');
        $flight->dep_time = $request->input('dep_time');
        $flight->dep_airport = $request->input('dep_airport');
        $flight->arr_airport = $request->input('arr_airport');
        $flight->est_arr_time = $request->input('est_arr_time');
        $flight->route = $request->input('route');
        $flight->save();

        return redirect('/dashboard/admin/realops')->with('success', 'That flight was created successfully');
    }

    public function showEditFlight($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'That flight does not exist');
        }

        return view('dashboard.admin.realops.edit')->with('flight', $flight);
    }
     
    public function editFlight(Request $request, $id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'That flight does not exist');
        }

        $request->validate([
            'flight_number' => 'required',
            'flight_date' => 'required|date_format:m/d/Y',
            'dep_time' => 'required|date_format:H:i',
            'dep_airport' => 'required',
            'arr_airport' => 'required',
            'est_arr_time' => 'date_format:H:i|nullable',
        ]);

        $flight->flight_number = $request->input('flight_number');
        $flight->flight_date = Carbon::parse($request->input('flight_date'))->format('Y-m-d');
        $flight->dep_time = $request->input('dep_time');
        $flight->dep_airport = $request->input('dep_airport');
        $flight->arr_airport = $request->input('arr_airport');
        $flight->est_arr_time = $request->input('est_arr_time');
        $flight->route = $request->input('route');
        $flight->save();

        $pilot = $flight->assigned_pilot;

        if ($pilot) {
            Mail::send('emails.realops.flight_updated', ['flight' => $flight, 'pilot' => $pilot], function ($message) use ($pilot, $flight) {
                $message->from('realops@notams.ztlartcc.org', 'ZTL Realops')->subject($this->emailSubIntro($flight->flight_number) . 'Flight Updated');
                $message->to($pilot->email);
            });
        }

        return redirect('/dashboard/admin/realops')->with('success', 'That flight was edited successfully');
    }

    public function bulkUploadFlights(Request $request) {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            $contents = file_get_contents($request->file('file')->getRealPath());
            Excel::import(new RealopsFlightImporter, request()->file('file'));
        } catch (Throwable $e) {
            return redirect('/dashboard/admin/realops')->with('error', 'Upload failed. Please check file format and try again');
        }

        return redirect('/dashboard/admin/realops')->with('success', 'Upload succeeded');
    }

    public function deleteFlight($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'That flight does not exist');
        }

        $pilot = $flight->assigned_pilot;
        $flight->delete();

        if ($pilot) {
            Mail::send('emails.realops.flight_cancelled', ['flight' => $flight, 'pilot' => $pilot], function ($message) use ($pilot, $flight) {
                $message->from('realops@notams.ztlartcc.org', 'ZTL Realops')->subject($this->emailSubIntro($flight->flight_number) . 'Flight Cancelled');
                $message->to($pilot->email);
            });
        }

        return redirect()->back()->with('success', 'That flight has been deleted successfully');
    }

    public function dumpData(Request $request) {
        if (strtolower($request->input('confirm_text')) != "confirm - dump all") {
            return redirect()->back()->with('error', 'Data not dumped. Please type in the required message to continue');
        }

        foreach (RealopsFlight::get() as $f) {
            $f->delete();
        }

        foreach (RealopsPilot::get() as $p) {
            $p->delete();
        }

        return redirect()->back()->with('success', 'The realops data has been dumped successfully');
    }

    private function emailSubIntro($flight_number) {
        return 'Realops Flight ' . $flight_number . ' - ';
    }
}
