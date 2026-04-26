<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CancelUnpaidOrders extends Command
{
    /**
     * Nama perintah yang dijalankan di terminal.
     * Gunakan: php artisan orders:cancel-unpaid
     */
    protected $signature = 'orders:cancel-unpaid';

    /**
     * Deskripsi perintah. 
     */
    protected $description = 'Membatalkan pesanan yang belum dibayar setelah melewati batas waktu 24 jam';

    public function handle()
    {
        $expiredTime = Carbon::now()->subHours(24);

        $orders = Order::where('status', 'menunggu_pembayaran')
                       ->where('created_at', '<', $expiredTime)
                       ->get();

        if ($orders->isEmpty()) {
            $this->info("Tidak ada pesanan yang kadaluwarsa saat ini.");
            return;
        }

        foreach ($orders as $order) {
            // Gunakan Transaction agar perubahan status & stok aman 
            DB::transaction(function () use ($order) {
                
                // 1. Kembalikan stok produk
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stok', $item->kuantitas);
                    }
                }

                // 2. Update status order
                $order->update([
                    'status' => 'dibatalkan',
                    'catatan_admin' => 'Dibatalkan otomatis oleh sistem (Melewati batas waktu pembayaran 24 jam).' 
                ]);

            });
            
            $this->info("Order ID #{$order->id} berhasil dibatalkan, stok dikembalikan.");
        }

        $this->info("Proses selesai: Total " . $orders->count() . " pesanan dibatalkan.");
    }
}