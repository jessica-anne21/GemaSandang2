<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan pencarian.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        // Fitur Pencarian (Nama atau Email)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru & Paginate
        $customers = $query->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Menampilkan detail pelanggan & riwayat pesanan.
     */
    public function show($id)
    {
        // Ambil user beserta order-nya (diurutkan order terbaru)
        $customer = User::with(['orders' => function($q) {
            $q->latest();
        }])->findOrFail($id);

        // Validasi role
        if ($customer->role !== 'customer') {
            return redirect()->route('admin.customers.index')
                ->with('error', 'User tersebut bukan pelanggan.');
        }

        // Hitung total belanja (Lifetime Value)
        $totalSpent = $customer->orders->where('status', 'selesai')->sum('total_harga');
        
        // Hitung total pesanan
        $totalPesananSelesai = Order::where('user_id', $id)
                                ->where('status', 'selesai')
                                ->count();

        return view('admin.customers.show', compact('customer', 'totalSpent', 'totalPesananSelesai'));
    }
}