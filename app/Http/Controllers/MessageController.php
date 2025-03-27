<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Get unread message count (existing method)
    public function getUnreadMessageCount()
    {
        $unreadMessages = Message::where('status', 'unread')->count();
        return view('dashboard.index', compact('unreadMessages'));
    }

    // Fetch messages between admin and a specific user
    public function fetchMessages($userEmail)
    {
        // Hardcoded admin email based on your description
        $adminEmail = 'admin2@example.com';
    
        // Fetch messages between the specific user and the admin
        $messages = Message::where(function($query) use ($adminEmail, $userEmail) {
            $query->where(function($q) use ($adminEmail, $userEmail) {
                $q->where('sender_email', $userEmail)
                  ->where('receiver_email', $adminEmail);
            })->orWhere(function($q) use ($adminEmail, $userEmail) {
                $q->where('sender_email', $adminEmail)
                  ->where('receiver_email', $userEmail);
            });
        })->orderBy('timestamp', 'asc')->get();
    
        // Mark messages from this user to admin as read
        Message::where('sender_email', $userEmail)
               ->where('receiver_email', $adminEmail)
               ->where('status', 'unread')
               ->update(['status' => 'read']);
    
        return response()->json($messages);
    }
    // Send a new message
public function sendMessage(Request $request)
{
    $request->validate([
        'receiver_email' => 'required|email',
        'message' => 'required|string|max:1000'
    ]);

    $message = Message::create([
        'sender_email' => 'admin2@example.com', // Hardcoded admin email
        'receiver_email' => $request->receiver_email,
        'message' => $request->message,
        'status' => 'unread',
        'timestamp' => now('Asia/Manila') // Specify Philippine timezone
    ]);

    return response()->json([
        'message' => 'Message sent successfully',
        'data' => $message
    ], 201);
}

// Get all users who have sent messages to admin
public function getUsersWithMessages()
{
    $adminEmail = 'admin2@example.com'; // Hardcoded admin email

    // Get unique users who have sent messages to this admin
    $users = Message::where('receiver_email', $adminEmail)
                    ->select('sender_email')
                    ->distinct()
                    ->get();

    return response()->json($users);
}

public function getUsersWithUnreadMessages()
{
    $adminEmail = 'admin2@example.com'; // Your admin email

    // Get users with unread messages sent to admin
    $users = Message::where('receiver_email', $adminEmail)
                    ->where('status', 'unread')
                    ->select('sender_email')
                    ->distinct()
                    ->get();

    return response()->json($users);
}

}