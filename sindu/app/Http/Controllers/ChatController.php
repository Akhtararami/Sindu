<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->orderBy('name')->get();
        $selectedUserId = request('user_id');

        $messages = collect();
        if ($selectedUserId) {
            $messages = ChatMessage::where(function ($query) use ($selectedUserId) {
                $query->where('sender_id', Auth::id())->where('receiver_id', $selectedUserId);
            })->orWhere(function ($query) use ($selectedUserId) {
                $query->where('sender_id', $selectedUserId)->where('receiver_id', Auth::id());
            })->orderBy('created_at')->get();

            ChatMessage::where('sender_id', $selectedUserId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('chat.index', compact('users', 'messages', 'selectedUserId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'user_id' => 'required|exists:users,id',
        ]);

        $receiver = User::findOrFail($request->user_id);

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($message->load(['sender', 'receiver']), 201);
    }

    public function messages(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $messages = ChatMessage::where(function ($query) use ($request) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $request->user_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->user_id)->where('receiver_id', Auth::id());
        })->orderBy('created_at')->get();

        ChatMessage::where('sender_id', $request->user_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages->load(['sender', 'receiver']));
    }

    public function unreadCount()
    {
        $count = ChatMessage::where('receiver_id', Auth::id())->where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }
}
