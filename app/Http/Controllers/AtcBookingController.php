<?php

namespace App\Http\Controllers;

use App\AtcBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

class AtcBookingController extends Controller
{
    public function viewBookings() {
        $bookings = AtcBooking::whereDate('end', '>=', Carbon::today())->get();
        return view('dashboard.controllers.bookings.view_bookings')->with('bookings', $bookings);
    }

    public function viewCreateBooking() {
        return view('dashboard.controllers.bookings.create_booking');
    }

    public function createBooking(Request $request) {
        $validator = $request->validate([
            'facility' => 'required',
            'position' => 'required',
            'type' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        $type = $request->type;
        if ($type == AtcBooking::TYPES["EVENT"] && ! (Auth::user()->hasRole('ec') || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'Only the EC can create event bookings')->withInput();
        }

        if ($type == AtcBooking::TYPES["EXAM"] && ! (Auth::user()->hasRole('ins') || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'Only instructors can create exam bookings')->withInput();
        }

        if ($type == AtcBooking::TYPES["MONITORING"] && ! (Auth::user()->isAbleTo('train') || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'Only mentors and instructors can create monitoring bookings')->withInput();
        }

        $booking = new AtcBooking;
        $booking->callsign = $request->facility . "_" . $request->position;
        $booking->cid = Auth::id();
        $booking->type = $type;
        $booking->start = $request->start;
        $booking->end = $request->end;
        $booking->save();

        return redirect('/dashboard/controllers/atc-bookings')->with('success', 'Your booking has been created successfully');
    }

    public function deleteBooking($id) {
        $booking = AtcBooking::find($id);

        if (! ($booking->cid == Auth::id() || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'You can only delete your own bookings');
        }

        $booking->delete();

        return redirect('/dashboard/controllers/atc-bookings')->with('success', 'Your booking has been deleted successfully');
    }
}
