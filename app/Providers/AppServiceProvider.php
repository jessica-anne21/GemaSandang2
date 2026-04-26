<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Order;
use App\Models\Bargain;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Mengatur Pagination agar menggunakan Bootstrap 5.
         */
        Paginator::useBootstrapFive();

        /**
         * View Composer untuk Navbar Customer (layouts.partials.navbar).
         */
        View::composer('layouts.partials.navbar', function ($view) {
            
            // A. Ambil Data Kategori untuk Dropdown
            $categories = Category::all();

            // B. Logic Badge Notifikasi Customer
            $badgeOrders = 0;
            $badgeBargains = 0;

            if (Auth::check()) {
                $userId = Auth::id();

                // Hitung order dengan status 'menunggu pembayaran'
                $badgeOrders = Order::where('user_id', $userId)
                    ->where('status', 'menunggu_pembayaran')
                    ->count();

                // Hitung tawaran yang sudah direspon Admin tapi belum dibaca Customer
                $badgeBargains = Bargain::where('user_id', $userId)
                    ->whereIn('status', ['accepted', 'rejected'])
                    ->where('is_read', false) 
                    ->count();
            }

            $view->with('categories', $categories)
                 ->with('badgeOrders', $badgeOrders)
                 ->with('badgeBargains', $badgeBargains);
        });

        View::composer('*', function ($view) {
            
            if (class_exists(Category::class)) {
                $view->with('categories', Category::all());
            }

        });

        /**
         * View Composer Khusus Layout Admin Sidebar.
         * Mengirimkan data notifikasi ke sidebar admin secara real-time.
         */
        View::composer('layouts.admin', function ($view) {
            
            // Hitung tawaran masuk status 'pending'
            $notifBargainAdmin = Bargain::where('status', 'pending')->count();

            // Hitung pesanan yang sudah upload bukti tapi belum dicek Admin
            // Status ini dipicu setelah Customer menekan tombol "Kirim Bukti"
            $notifOrderAdmin = Order::where('status', 'menunggu_konfirmasi')->count();

            $view->with('notifBargainAdmin', $notifBargainAdmin)
                 ->with('notifOrderAdmin', $notifOrderAdmin);
        });
    }
}