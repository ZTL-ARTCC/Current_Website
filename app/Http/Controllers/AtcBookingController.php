<?php

namespace App\Http\Controllers;

use App\AtcBooking;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AtcBookingController extends Controller {
    public function viewBookings() {
        $today = Carbon::today();
        $bookings = AtcBooking::whereDate('start', '>=', $today)
            ->where(function ($query) use ($today) {
                $query->whereDate('end', '<=', $today->addDays(14))
                      ->orWhere('cid', Auth::id());
            })
            ->orderBy('start', 'ASC')
            ->get();

        $bookings = groupAtcBookingsByDate($bookings);

        $types = [
            AtcBooking::TYPES['BOOKING'] => "Booking"
        ];

        if (Auth::user()->isAbleTo('train') || Auth::user()->isAbleTo('snrStaff')) {
            $types[AtcBooking::TYPES['MONITORING']] = "Monitoring";
        }

        if (Auth::user()->hasRole('events-team') || Auth::user()->isAbleTo('snrStaff')) {
            $types[AtcBooking::TYPES['EVENT']] = "Event";
        }

        if (Auth::user()->hasRole('ins') || Auth::user()->isAbleTo('snrStaff')) {
            $types[AtcBooking::TYPES['EXAM']] = "Exam";
        }

        return view('dashboard.controllers.bookings')->with('bookings', $bookings)->with('types', $types);
    }

    public function createBooking(Request $request) {
        $validator = $request->validate([
            'facility' => 'required',
            'position' => 'required',
            'type' => 'required',
            'start' => 'required|date_format:m/d/Y H:i|after:now',
            'end' => 'required|date_format:m/d/Y H:i|after:start',
        ]);

        $callsign = $request->facility . "_" . $request->position;
        $start = Carbon::createFromFormat('m/d/Y H:i', $request->start)->toDateTimeString();
        $end = Carbon::createFromFormat('m/d/Y H:i', $request->end)->toDateTimeString();

        $existing = AtcBooking::where('callsign', $callsign)
                              ->where(function ($query) use ($start, $end) {
                                  $query->where(function ($query) use ($start) {
                                      $query->where('start', '>=', $start)
                                              ->where('end', '<', $start);
                                  })
                                     ->orWhere(function ($query) use ($end) {
                                         $query->where('start', '<', $end)
                                                 ->where('end', '>=', $end);
                                     });
                              })->count() > 0;

        if ($existing) {
            return redirect()->back()->with('error', 'A booking already exists for ' . $callsign . ' at this time')->withInput();
        }

        $type = $request->type;
        if ($type == AtcBooking::TYPES["EVENT"] && ! (Auth::user()->hasRole('events-team') || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'Only the EC can create event bookings')->withInput();
        }

        if ($type == AtcBooking::TYPES["EXAM"] && ! (Auth::user()->hasRole('ins') || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'Only instructors can create exam bookings')->withInput();
        }

        if ($type == AtcBooking::TYPES["MONITORING"] && ! (Auth::user()->isAbleTo('train') || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'Only mentors and instructors can create monitoring bookings')->withInput();
        }

        $booking = new AtcBooking;
        $booking->callsign = $callsign;
        $booking->cid = Auth::id();
        $booking->type = $type;
        $booking->start = $start;
        $booking->end = $end;
        $booking->save();

        return redirect('/dashboard/controllers/bookings')->with('success', 'Your booking has been created successfully');
    }

    public function deleteBooking($id) {
        $booking = AtcBooking::find($id);

        if (! ($booking->cid == Auth::id() || Auth::user()->isAbleTo('snrStaff'))) {
            return redirect()->back()->with('error', 'You can only delete your own bookings');
        }

        $booking->delete();

        return redirect()->back()->with('success', 'Your booking has been deleted successfully');
    }
}
