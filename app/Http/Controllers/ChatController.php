<?php

namespace App\Http\Controllers;

use App\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getMessages() {
        $messages = Chat::where('deleted', 0)->orderBy('created_at')->get();

        return json_encode(['success' => true, 'messages' => $messages]);
    }
}
