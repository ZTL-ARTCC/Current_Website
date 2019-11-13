<?php

namespace App\Http\Controllers\Mship\Feedback;

use App\Mship\Feedback;
use App\Mship\User;
use Logs\Audit;
use Auth;
use Mail;

class FeedbackUser extends \App\Http\Controllers\Controller
{
    public function newFeedback() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        return view('site.feedback')->with('controllers', $controllers);
    }

    public function saveNewFeedback(Request $request) {
        $validatedData = $request->validate([
            'controller' => 'required',
            'position' => 'required',
            'callsign' => 'required',
            'pilot_name' => 'required',
            'pilot_email' => 'required',
            'pilot_cid' => 'required',
            'comments' => 'required'
        ]);

        //Google reCAPTCHA Verification
        $client = new Client;
        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => Config::get('google.recaptcha'),
                'response' => $request->input('g-recaptcha-response'),
            ]
        ]);
        $r = json_decode($response->getBody())->success;
        if($r != true) {
            return redirect()->back()->with('error', 'You must complete the ReCaptcha to continue.');
        }

        //Continue Request
        $feedback = new Feedback;
        $feedback->controller_id = Input::get('controller');
        $feedback->position = Input::get('position');
        $feedback->service_level = Input::get('service');
        $feedback->callsign = Input::get('callsign');
        $feedback->pilot_name = Input::get('pilot_name');
        $feedback->pilot_email = Input::get('pilot_email');
        $feedback->pilot_cid = Input::get('pilot_cid');
        $feedback->comments = Input::get('comments');
        $feedback->status = 0;
        $feedback->save();

        return redirect('/')->with('success', 'Thank you for the feedback! It has been recieved successfully.');
    }
}