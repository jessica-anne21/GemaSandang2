<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; 
use Carbon\Carbon; 


class OrderController extends Controller
{
    public function index()
    {
        // Ambil pesanan milik pengguna yang sedang login, diurutkan dari yang terbaru
        $orders = Auth::user()->orders()->latest()->get();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Menandai pesanan sebagai selesai (Diterima oleh customer).
     */
    public function markAsCompleted(Request $request, $orderId)
    {
        // 1. Cari Order milik user yang sedang login
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        // 2. Validasi: Hanya bisa terima jika statusnya 'dikirim's
        if ($order->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Pesanan tidak dapat diselesaikan saat ini.');
        }

        // 3. Update Status & Tanggal
        $order->update([
            'status' => 'selesai',
            'tanggal_diterima' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Pesanan telah selesai.');
    }

    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())
                    ->where('id', $id)
                    ->firstOrFail();

        return view('customer.orders.show', compact('order'));
    }
}