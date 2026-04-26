<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(10); 

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product.category'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        // Ambil data beserta relasi items dan product untuk keperluan stok
        $order = Order::with('items.product')->findOrFail($id); 
    
        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,menunggu_konfirmasi,diproses,dikirim,selesai,dibatalkan',
            'nomor_resi' => 'nullable|string|max:255',
        ]);
        
        if ($request->status == 'selesai' && $order->status != 'dikirim') {
            return back()->with('warning', 'Status hanya bisa diselesaikan jika barang sudah dikirim (sudah ada nomor resi).');
        }

        if ($request->status == 'dikirim' && empty($request->nomor_resi)) {
            return back()->with('warning', 'Nomor resi wajib diisi jika status pesanan diubah menjadi DIKIRIM.');
        }
    
        if ($request->status == 'dibatalkan' && $order->status != 'dibatalkan') {
            DB::transaction(function () use ($order, $request) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stok', $item->kuantitas);
                    }
                }
                $order->update([
                    'status' => $request->status,
                    'nomor_resi' => $request->nomor_resi
                ]);
            });
        } else {
            $order->update([
                'status' => $request->status,
                'nomor_resi' => $request->nomor_resi
            ]);
        }
    
        return redirect()->route('admin.orders.show', $order->id)
                         ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:255'
        ]);

        if ($order->bukti_bayar) {
            Storage::disk('public')->delete($order->bukti_bayar);
        }

        $order->update([
            'status' => 'menunggu_pembayaran', 
            'bukti_bayar' => null, 
            'catatan_admin' => $request->catatan_admin 
        ]);

        return redirect()->back()->with('success', 'Pembayaran ditolak. Customer diminta upload ulang.');
    }
    
    public function cancelByAdmin($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
    
        if (in_array($order->status, ['menunggu_pembayaran', 'diproses'])) {
            DB::transaction(function () use ($order) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stok', $item->kuantitas);
                    }
                }
                $order->update(['status' => 'dibatalkan']);
            });
    
            return redirect()->back()->with('success', 'Pesanan dibatalkan dan stok telah dikembalikan.');
        }
    
        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }
}