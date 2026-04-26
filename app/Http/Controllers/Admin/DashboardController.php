<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Order;   
use App\Models\Category;
use App\Models\User;    
use App\Models\Bargain; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\OrderItem; 


class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data statistik.
     */
    public function index()
    {
        $totalProducts = Product::count();

        // Mengambil pesanan yang butuh konfirmasi/diproses
        $newOrders = Order::whereIn('status', ['pending', 'sudah_bayar'])->count();

        $totalCustomers = User::where('role', 'customer')->count();

        // Mengambil 5 tawaran terbaru yang masih pending
        $recentBargains = Bargain::with(['product', 'user'])
                            ->where('status', 'pending')
                            ->latest()
                            ->take(5)
                            ->get();

        $recentOrders = Order::with('user')
                             ->latest()
                             ->take(5)
                             ->get();

        // Top 3 Kategori Terlaris
        $topCategories = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('categories.nama_kategori', DB::raw('count(order_items.id) as total_terjual'))
            ->where('orders.status', 'selesai')
            ->groupBy('categories.id', 'categories.nama_kategori') 
            ->orderBy('total_terjual', 'desc')
            ->take(3)
            ->get();

        // Penjualan Harian per 30 Hari
        $salesPerDay = Order::selectRaw('DATE(created_at) as date, SUM(total_harga) as total')
            ->where('status', 'selesai')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->take(30)
            ->get();

        // Data untuk grafik
        $labels = $salesPerDay->pluck('date');
        $data   = $salesPerDay->pluck('total');
        $totalRevenue = Order::where('status', 'selesai')->sum('total_harga');

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalRevenue',
            'totalCustomers',
            'recentBargains',
            'recentOrders',
            'topCategories',
            'labels',
            'data'
        ));
    }
}