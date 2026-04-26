<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BarterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarterController extends Controller
{
    public function index()
    {
        // STEP 1: Coba tarik SEMUA barang tanpa filter apapun
        // Kalau ini muncul, berarti datanya ada di DB.
        $barterItems = BarterItem::with('user')->latest()->paginate(12);

        // STEP 2: Kalau Step 1 muncul, coba aktifkan filter satu per satu:
        // $barterItems = BarterItem::where('status', 'available') 
        //     ->where('user_id', '!=', auth()->id()) 
        //     ->whereHas('user', function($query) {
        //         $query->whereIn('status', ['verified', 'Verified']);
        //     })
        //     ->with('user')
        //     ->latest()
        //     ->paginate(12);

        return view('customer.barter.index', compact('barterItems'));
    }

    public function show($id)
    {
        $item = \App\Models\BarterItem::with('user')->findOrFail($id);

        $userProducts = \App\Models\BarterItem::where('user_id', auth()->id())
                                            ->where('status', 'available')
                                            ->get();

        return view('customer.barter.show', compact('item', 'userProducts'));
    }

    public function sendRequest(Request $request, $id)
    {
        // 1. Validasi pilihan barang
        $request->validate([
            'my_item_id' => 'required|exists:barter_items,id',
        ]);

        // 2. Simpan penawaran (Contoh logic simpan ke tabel requests)
        // Untuk sementara, kita pakai dd() dulu buat ngetes datanya masuk atau ngga
        /*
        \App\Models\BarterRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver_id, 
            'requested_item_id' => $id,
            'offered_item_id' => $request->my_item_id,
            'message' => $request->pesan,
            'status' => 'pending'
        ]);
        */

        return redirect()->route('barter.index')->with('success', 'Penawaran barter berhasil dikirim! Tunggu jawaban dari pemilik ya.');
    }
}