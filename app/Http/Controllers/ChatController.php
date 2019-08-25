<?php

namespace App\Http\Controllers;

use App\Chat;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages() {
        $messages = Chat::where('deleted', 0)->orderBy('created_at', 'DESC')->get();

        return json_encode(['success' => true, 'messages' => $messages]);
    }

    public function newMessage(Request $request) {
        $time_now = Carbon::now();
        $time_format = $time_now->format('m/d/y H:i');

        $message = new Chat();
        $message->cid = $request->cid;
        $message->c_name = User::find($request->cid)->full_name;
        $message->message = $request->message;
        $message->deleted = 0;
        $message->format_time = $time_format;
        $message->save();

        return json_encode(['success' => true]);
    }

    public function deleteMessage(Request $request, $id) {
        $message = Chat::find($id);
        $requester = User::find($request->cid);

        if($request->cid == $message->cid || $requester->can('snrStaff'))
            $message->deleted = 1;

        $message->save();

        return json_encode(['success' => true]);
    }
}
