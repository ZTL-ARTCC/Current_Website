<?php

namespace App\Http\Controllers;

use App\Exports\RealopsExport;
use App\Importers\RealopsFlightImporter;
use App\Mail\Realops;
use App\RealopsFlight;
use App\RealopsPilot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mail;

class RealopsController extends Controller {
    public function index(Request $request) {
        $airport_filter = $request->get('airport_filter');
        $flightno_filter = $request->get('flightno_filter');
        $date_filter = $request->get('date_filter');
        $time_filter = $request->get('time_filter');
        
        $flights = RealopsFlight::where('flight_number', 'like', '%' . $flightno_filter . '%')
                                ->when(! is_null($time_filter), function ($query) use ($time_filter) {
                                    $times = $this->timeBetween($time_filter, 15, 45);
                                    $query->whereTime('dep_time', ">=", Carbon::parse($times[0]))
                                        ->whereTime('dep_time', "<=", Carbon::parse($times[1]));
                                })
                                ->when(! is_null($date_filter), function ($query) use ($date_filter) {
                                    $query->where('flight_date', $date_filter);
                                })
                                ->where(function ($query) use ($airport_filter) {
                                    $query->where('dep_airport', 'like', '%' . $airport_filter . '%')
                                          ->orWhere('arr_airport', 'like', '%' . $airport_filter . '%');
                                })
                                ->orderBy('flight_date', 'ASC')
                                ->orderBy('dep_time', 'ASC')
                                ->paginate(20);

        return view('site.realops')->with('flights', $flights)->with('airport_filter', $airport_filter)->with('flightno_filter', $flightno_filter)->with('date_filter', $date_filter)->with('time_filter', $time_filter);
    }

    public function bid($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'That flight doesn\'t exist');
        }

        if ($flight->assigned_pilot_id) {
            return redirect()->back()->with('error', 'That flight already has a pilot assigned');
        }
        
        $pilot = auth()->guard('realops')->user();
        $flight->assignPilotToFlight($pilot->id);

        Mail::to($pilot->email)->send(new Realops($flight, $pilot, 'bid'));

        return redirect()->back()->with('success', 'You have bid for that flight successfully. You should receive a confirmation email soon and will receive email updates regarding your flight');
    }

    public function cancelBid($id) {
        $flight = RealopsFlight::find($id);
        if (! $flight) {
            return redirect()->back()->with('error', 'That flight doesn\'t exist');
        }

        $pilot = auth()->guard('realops')->user();
        if ($pilot->id != $flight->assigned_pilot_id) {
            return redirect()->back()->with('error', 'That flight isn\'t assigned to you');
        }
        $flight->removeAssignedPilot();

        Mail::to($pilot->email)->send(new Realops($flight, $pilot, 'cancel_bid'));

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

        Mail::to($pilot->email)->send(new Realops($flight, $pilot, 'assigned_flight'));

        return redirect()->back()->with('success', 'That pilot was assigned successfully');
    }

    public function removePilotFromFlight($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'Unable to remove the assigned pilot from that flight');
        }

        $pilot = $flight->assigned_pilot;

        
        $flight->removeAssignedPilot();

        Mail::to($pilot->email)->send(new Realops($flight, $pilot, 'removed_from_flight'));

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
            'est_time_enroute' => 'date_format:H:i|nullable',
        ]);

        $flight = new RealopsFlight;
        $flight->flight_number = $request->input('flight_number');
        $flight->callsign = $request->input('callsign');
        $flight->flight_date = Carbon::parse($request->input('flight_date'))->format('Y-m-d');
        $flight->dep_time = $request->input('dep_time');
        $flight->dep_airport = $request->input('dep_airport');
        $flight->arr_airport = $request->input('arr_airport');
        $flight->est_time_enroute = $request->input('est_time_enroute');
        $flight->gate = $request->input('gate');
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
            'est_time_enroute' => 'date_format:H:i|nullable',
        ]);

        $flight->flight_number = $request->input('flight_number');
        $flight->callsign = $request->input('callsign');
        $flight->flight_date = Carbon::parse($request->input('flight_date'))->format('Y-m-d');
        $flight->dep_time = $request->input('dep_time');
        $flight->dep_airport = $request->input('dep_airport');
        $flight->arr_airport = $request->input('arr_airport');
        $flight->est_time_enroute = $request->input('est_time_enroute');
        $flight->gate = $request->input('gate');
        $flight->save();

        $pilot = $flight->assigned_pilot;

        if ($pilot) {
            Mail::to($pilot->email)->send(new Realops($flight, $pilot, 'flight_updated'));
        }

        return redirect('/dashboard/admin/realops')->with('success', 'That flight was edited successfully');
    }

    public function bulkUploadFlights(Request $request) {
        $request->validate([
            'file' => 'required|file'
        ]);

        try {
            // what is this for?
            // this doesn't do anything
            //$contents = file_get_contents($request->file('file')->getRealPath());
            Excel::import(new RealopsFlightImporter, request()->file('file'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = "";

            foreach ($failures as $failure) {
                // L21#0 Blah blah blah (value: 'fbalsdhj')
                Log::info($failure);
                $errors = $errors.' L'.$failure->row().'#'.$failure->attribute().': '.join(',', $failure->errors()).' ('.$failure->values()[$failure->attribute()].')';
            }
            return redirect('/dashboard/admin/realops')->with('error', 'Upload failed. Errors: ' . $errors);
        } catch (\Exception $e) {
            return redirect('/dashboard/admin/realops')->with('error', 'Upload failed. Check your formatting: ' . $e->getMessage());
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
            Mail::to($pilot->email)->send(new Realops($flight, $pilot, 'flight_cancelled'));
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

    public function exportData() {
        return Excel::download(new RealopsExport, 'ztl_realops_' . Carbon::now()->timestamp . '.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => Carbon::now()->toRfc7231String()
        ]);
    }

    private function timeBetween($time, $min_before, $min_after) {
        $time_split = explode(':', $time);
        $hour = intval($time_split[0]);
        $min = intval($time_split[1]);

        $hour_first = $hour;
        $min_first = $min - $min_before;

        if ($min_first < 0) {
            $hour_first = $hour_first == 0 ? 23 : $hour_first - 1;
            $min_first += 60;
        }

        $hour_last = $hour;
        $min_last = $min + $min_after;

        if ($min_last >= 60) {
            $hour_last = $hour_last == 23 ? 0 : $hour_last + 1;
            $min_last -= 60;
        }

        return [
            sprintf('%02d', $hour_first) . ':' . sprintf('%02d', $min_first),
            sprintf('%02d', $hour_last) . ':' . sprintf('%02d', $min_last)
        ];
    }
}
