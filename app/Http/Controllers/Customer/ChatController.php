<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use App\Models\BarterRequest; // Panggil model request barter kita

class ChatController extends Controller
{
    // Menampilkan daftar chat aktif (Hanya yang status barternya Accepted)
    public function index()
    {
        $myId = Auth::id();
        
        $chatList = BarterRequest::where('status', 'accepted')
            ->where(function($q) use ($myId) {
                $q->where('sender_id', $myId)
                  ->orWhere('receiver_id', $myId);
            })
            ->with(['sender', 'receiver', 'requestedItem', 'offeredItem'])
            ->latest()
            ->get();

        return view('customer.chat_index', compact('chatList'));
    }

    // Menampilkan room chat spesifik untuk satu transaksi barter
    public function show($barter_request_id)
    {
        $myId = Auth::id();
        
        // 1. Cek dulu transaksinya ada gak, dan statusnya accepted gak?
        $barterInfo = BarterRequest::with(['sender', 'receiver', 'requestedItem', 'offeredItem'])
                        ->findOrFail($barter_request_id);
        
        if($barterInfo->status !== 'accepted') {
            return redirect()->route('barter.inbox')->with('error', 'Barter belum disetujui!');
        }

        // 2. Tentukan siapa lawannya (kalau kita sender, berarti lawan kita receiver, vice versa)
        $receiver = ($barterInfo->sender_id == $myId) ? $barterInfo->receiver : $barterInfo->sender;

        // 3. Tarik pesan yang cuma nempel di barter_request_id ini
        $messages = DB::table('messages')
            ->where('barter_request_id', $barter_request_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('customer.chat', compact('messages', 'receiver', 'barterInfo'));
    }

    public function sendMessage(Request $request)
    {
        try {
            $data = [
                'sender_id'         => Auth::id(),
                'receiver_id'       => $request->receiver_id,
                'barter_request_id' => $request->barter_request_id, // Masukin ID barternya!
                'isi_pesan'         => $request->message,
                'created_at'        => now(),
            ];

            DB::table('messages')->insert($data);

            // Kirim event buat Realtime-nya
            event(new MessageSent($data));

            return response()->json(['status' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}