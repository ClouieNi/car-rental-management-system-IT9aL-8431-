<?php

namespace App\Http\Controllers;

use App\Models\CustomerMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = CustomerMessage::with(['user', 'rental.car'])
                                   ->orderBy('is_read')
                                   ->orderByDesc('created_at')
                                   ->paginate(20);

        return view('messages.index', compact('messages'));
    }

    public function show(CustomerMessage $message)
    {
        $message->update(['is_read' => true, 'read_at' => now()]);
        $message->load(['user', 'rental.car']);
        return view('messages.show', compact('message'));
    }

    public function reply(Request $request, CustomerMessage $message)
    {
        $request->validate(['reply' => 'required|string|max:2000']);

        $message->update([
            'admin_reply' => $request->reply,
            'replied_at'  => now(),
        ]);

        return back()->with('success', 'Reply sent to ' . $message->user->name);
    }
}