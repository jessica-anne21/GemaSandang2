<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $myId = Auth::id();
        $chatList = DB::table('messages')
            ->join('users', function($join) use ($myId) {
                $join->on('messages.sender_id', '=', 'users.id')
                    ->orOn('messages.receiver_id', '=', 'users.id');
            })
            ->where(function($q) use ($myId) {
                $q->where('messages.sender_id', $myId)
                  ->orWhere('messages.receiver_id', $myId);
            })
            ->where('users.id', '!=', $myId)
            ->select('users.id', 'users.name', DB::raw('MAX(messages.created_at) as last_chat'))
            ->groupBy('users.id', 'users.name')
            ->orderBy('last_chat', 'desc')
            ->get();

        return view('customer.chat_index', compact('chatList'));
    }

    public function show($receiver_id)
    {
        $myId = Auth::id();
        $receiver = DB::table('users')->where('id', $receiver_id)->first();
        
        $messages = DB::table('messages')
            ->where(function($q) use ($myId, $receiver_id) {
                $q->where('sender_id', $myId)->where('receiver_id', $receiver_id);
            })
            ->orWhere(function($q) use ($myId, $receiver_id) {
                $q->where('sender_id', $receiver_id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('customer.chat', compact('messages', 'receiver'));
    }

    public function sendMessage(Request $request)
{
    try {
        $data = [
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'isi_pesan'   => $request->message,
            'created_at'  => now(),
        ];

        DB::table('messages')->insert($data);

        event(new MessageSent($data));

        return response()->json(['status' => 'Success']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}