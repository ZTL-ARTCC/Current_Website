<?php

namespace App\Http\Controllers\Mship\Feedback;

use App\Mship\Feedback;
use App\Mship\User;
use Logs\Audit;
use Auth;
use Mail;

class FeedbackController extends \App\Http\Controllers\Controller
{
    public function showFeedback() {
        $controllers = User::where('status', 1)->orderBy('lname', 'ASC')->get()->pluck('backwards_name', 'id');
        $feedback = Feedback::where('status', 0)->orderBy('created_at', 'ASC')->get();
        $feedback_p = Feedback::where('status', 1)->orwhere('status', 2)->orderBy('updated_at', 'DSC')->paginate(25);
        return view('dashboard.admin.feedback')->with('feedback', $feedback)->with('feedback_p', $feedback_p)->with('controllers', $controllers);
    }

    public function saveFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->controller_id = $request->controller_id;
        $feedback->position = $request->position;
        $feedback->staff_comments = $request->staff_comments;
        $feedback->comments = $request->pilot_comments;
        $feedback->status = 1;
        $feedback->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' saved feedback '.$feedback->id.' for '.$feedback->controller_name.'.';
        $audit->save();

        $controller = User::find($feedback->controller_id);

        Mail::send(['html' => 'emails.new_feedback'], ['feedback' => $feedback, 'controller' => $controller], function($m) use ($feedback, $controller) {
            $m->from('feedback@notams.ztlartcc.org', 'vZTL ARTCC Feedback Department');
            $m->subject('You Have New Feedback!');
            $m->to($controller->email);
        });

        return redirect()->back()->with('success', 'The feedback has been saved.');
    }

    public function hideFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->controller_id = $request->controller_id;
        $feedback->position = $request->position;
        $feedback->staff_comments = $request->staff_comments;
        $feedback->comments = $request->pilot_comments;
        $feedback->status = 2;
        $feedback->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' archived feedback '.$feedback->id.' for '.$feedback->controller_name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The feedback has been hidden.');
    }

    public function updateFeedback(Request $request, $id) {
        $feedback = Feedback::find($id);
        $feedback->controller_id = $request->controller_id;
        $feedback->position = $request->position;
        $feedback->staff_comments = $request->staff_comments;
        $feedback->comments = $request->pilot_comments;
        $feedback->status = $request->status;
        $feedback->save();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' updated feedback '.$feedback->id.' for '.$feedback->controller_name.'.';
        $audit->save();

        return redirect()->back()->with('success', 'The feedback has been updated.');
    }

    public function emailFeedback(Request $request, $id) {
        $validator = $request->validate([
            'email' => 'required',
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required'
        ]);

        $feedback = Feedback::find($id);
        $replyTo = $request->email;
        $replyToName = $request->name;
        $subject = $request->subject;
        $body = $request->body;
        $sender = Auth::user();

        $audit = new Audit;
        $audit->cid = Auth::id();
        $audit->ip = $_SERVER['REMOTE_ADDR'];
        $audit->what = Auth::user()->full_name.' emailed the pilot for feedback '.$feedback->id.'.';
        $audit->save();

        Mail::send('emails.feedback_email', ['feedback' => $feedback, 'body' => $body, 'sender' => $sender], function($m) use ($feedback, $subject, $replyTo, $replyToName) {
            $m->from('feedback@notams.ztlartcc.org', 'vZTL ARTCC Feedback Department')->replyTo($replyTo, $replyToName);
            $m->subject($subject);
            $m->to($feedback->pilot_email);
        });

        return redirect()->back()->with('success', 'The email has been sent to the pilot successfully.');
    }
}