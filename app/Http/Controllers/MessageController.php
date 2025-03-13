<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;


class MessageController extends Controller
{
    public function countUnreadMessages()
    {
        $unreadMessages = Message::where('status', 'unread')->count(); // Count only unread messages
        return view('dashboard.index', compact('unreadMessages')); // Pass the count to the view
    }
}
