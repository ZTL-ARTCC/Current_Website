<?php

namespace App\Http\Controllers;

use App\Importers\RealopsFlightImporter;
use App\RealopsFlight;
use App\RealopsPilot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class RealopsController extends Controller {
    public function index() {
        $flights = RealopsFlight::orderBy('dep_time', 'ASC')->paginate(20);
        return view('site.realops')->with('flights', $flights);
    }

    public function bid($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'That flight doesn\'t exist');
        }
        
        $flight->assignPilotToFlight(auth()->guard()->id());
        return redirect()->back()->with('success', 'You have bid for that flight successfully. You should receive a confirmation email soon and will receive email updates regarding your flight');
    }

    public function cancelBid() {
        $flight = auth()->guard('realops')->user()->assigned_flight;

        if (! $flight) {
            return redirect()->back()->with('error', 'You have not yet bid for a flight');
        }
        
        $flight->removeAssignedPilot();
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

        $flight->assignPilotToFlight($request->input('pilot'));

        return redirect()->back()->with('success', 'That pilot was assigned successfully');
    }

    public function removePilotFromFlight($id) {
        $flight = RealopsFlight::find($id);

        if (! $flight) {
            return redirect()->back()->with('error', 'Unable to remove the assigned pilot from that flight');
        }

        $flight->removeAssignedPilot();
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
            'arr_airport' => 'required'
        ]);

        $flight = new RealopsFlight;
        $flight->flight_number = $request->input('flight_number');
        $flight->flight_date = Carbon::parse($request->input('flight_date'))->format('Y-m-d');
        $flight->dep_time = $request->input('dep_time');
        $flight->dep_airport = $request->input('dep_airport');
        $flight->arr_airport = $request->input('arr_airport');
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
            'arr_airport' => 'required'
        ]);

        $flight->flight_number = $request->input('flight_number');
        $flight->flight_date = Carbon::parse($request->input('flight_date'))->format('Y-m-d');
        $flight->dep_time = $request->input('dep_time');
        $flight->dep_airport = $request->input('dep_airport');
        $flight->arr_airport = $request->input('arr_airport');
        $flight->route = $request->input('route');
        $flight->save();

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
}
