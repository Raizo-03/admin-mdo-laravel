<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;


class MessageController extends Controller
{
    public function getUnreadMessageCount() // Changed function name
    {
        $unreadMessages = Message::where('status', 'unread')->count();
        return view('dashboard.index', compact('unreadMessages'));
    }
}
