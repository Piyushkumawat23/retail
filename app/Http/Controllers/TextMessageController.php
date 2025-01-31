<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class TextMessageController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'sender' => 'required|string',
            'content' => 'required|string',
        ]);

        // Store the message in the database
        $message = new TextMessage();
        $message->sender = $request->input('sender');
        $message->content = $request->input('content');
        $message->save();

        return response()->json(['message' => 'Message sent successfully'], 201);
    }

    public function receiveReply(Request $request)
    {
        // Process incoming reply from phone
        // For simplicity, let's assume the phone number and reply message are sent as parameters in the request
        $phoneNumber = $request->input('phone_number');
        $replyMessage = $request->input('reply_message');

        // Update the corresponding message in the database
        $message = TextMessage::where('phone_number', $phoneNumber)->orderBy('created_at', 'desc')->first();
        if ($message) {
            $message->reply = $replyMessage;
            $message->save();
            return response()->json(['message' => 'Reply received and updated successfully']);
        } else {
            return response()->json(['error' => 'Message not found'], 404);
        }
    }
}

