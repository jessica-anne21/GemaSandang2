<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use App\Models\BarterRequest;
use App\Models\User;
use App\Models\Message; // Pastikan model yang kita buat tadi dipanggil

class ChatController extends Controller
{
    /**
     * Menampilkan daftar chat aktif berdasarkan transaksi barter.
     * Kita ambil semua status yang sudah 'accepted' ke atas (ongoing, completed, rejected_qc)
     * agar riwayat chat tidak hilang setelah transaksi selesai.
     */
    // Di ChatController.php bagian index()
public function index()
{
    $myId = Auth::id();
    
    $chatList = BarterRequest::whereIn('status', ['accepted', 'on_going', 'completed', 'rejected_qc'])
        ->where(function($q) use ($myId) {
            $q->where('sender_id', $myId)
              ->orWhere('receiver_id', $myId);
        })
        ->with(['sender', 'receiver', 'requestedItem', 'offeredItem'])
        // Tambahkan hitungan pesan yang belum dibaca
        ->withCount(['messages as unread_count' => function($q) use ($myId) {
            $q->where('receiver_id', $myId)->where('is_read', 0);
        }])
        ->latest('updated_at')
        ->get();

    return view('customer.chat_index', compact('chatList'));
}

    /**
     * Menampilkan room chat spesifik untuk satu transaksi barter.
     */
    public function show($user_id, $barter_id)
    {
        $myId = Auth::id();
        $partner = User::findOrFail($user_id);
        $barter = BarterRequest::with(['offeredItem', 'requestedItem'])->findOrFail($barter_id);

        // Security Check: Pastikan user yang buka chat adalah pelaku barter tersebut
        if ($barter->sender_id != $myId && $barter->receiver_id != $myId) {
            abort(403, 'Anda tidak memiliki akses ke percakapan ini.');
        }

        // Ambil pesan yang HANYA berhubungan dengan transaksi barter ini
        $messages = Message::where('barter_request_id', $barter_id)
            ->where(function($q) use ($user_id, $myId) {
                $q->where('sender_id', $myId)->where('receiver_id', $user_id)
                  ->orWhere('sender_id', $user_id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Fitur Mark as Read: Tandai semua pesan masuk sebagai terbaca saat dibuka
        Message::where('barter_request_id', $barter_id)
            ->where('receiver_id', $myId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('customer.chat', compact('partner', 'barter', 'messages'));
    }

    /**
     * Mengirim pesan via AJAX.
     */
public function sendMessage(Request $request)
{
    // 1. Perbaiki validasi agar teks boleh kosong kalau ada gambar
    $request->validate([
        'receiver_id' => 'required',
        'barter_request_id' => 'required',
        'message' => 'nullable|string', // nullable berarti boleh kosong
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    try {
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan ke storage/app/public/chat_images
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        // Jika pesan kosong DAN gambar juga kosong, jangan simpan apa-apa
        if (!$request->message && !$imagePath) {
            return response()->json(['status' => 'Error', 'message' => 'Pesan kosong'], 400);
        }

        // 2. Gunakan Model Message untuk create
        $message = Message::create([
            'sender_id'         => auth()->id(),
            'receiver_id'       => $request->receiver_id,
            'barter_request_id' => $request->barter_request_id,
            'isi_pesan'         => $request->message ?? '', // Beri string kosong jika null
            'image'             => $imagePath,
            'is_read'           => 0,
        ]);

        return response()->json([
            'status' => 'Success',
            'data'   => [
                'isi_pesan' => $message->isi_pesan,
                'image_url' => $message->image ? asset('storage/' . $message->image) : null,
                'created_at' => $message->created_at->format('H:i')
            ]
        ]);
    } catch (\Exception $e) {
        // Cek pesan error di tab Preview/Response F12
        return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 500);
    }
}
}