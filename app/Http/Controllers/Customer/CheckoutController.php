<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Keranjang Anda kosong.');
        }

        return view('customer.checkout.index', compact('cartItems'));
    }

    public function store(Request $request)
    {        
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:500',
            'kurir' => 'required|string',
            'ongkir' => 'required|numeric',
            'nomor_hp' => 'required|string|max:15',
            'catatan_customer' => 'nullable|string|max:1000', 
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop');
        }

        $user = Auth::user();
        if ($user->alamat !== $request->alamat_pengiriman || $user->nomor_hp !== $request->nomor_hp) {
            $user->update([
                'alamat' => $request->alamat_pengiriman,
                'nomor_hp' => $request->nomor_hp
            ]);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->harga * $item->kuantitas;
            }
            $grandTotal = $subtotal + $request->ongkir;

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $grandTotal,
                'status' => 'menunggu_pembayaran', 
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'kurir' => $request->kurir,
                'ongkir' => $request->ongkir,
                'nomor_hp' => $request->nomor_hp, 
                'catatan_customer' => $request->catatan_customer, 
            ]);

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->find($item->product_id); 

                if (!$product || $product->stok < $item->kuantitas) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'Maaf, produk "' . ($product ? $product->nama_produk : 'Unknown') . '" baru saja habis terjual.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'kuantitas' => $item->kuantitas,
                    'harga_saat_beli' => $item->harga, 
                ]);

                $product->decrement('stok', $item->kuantitas);
            }

            Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            
            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        return view('customer.checkout.success', compact('order'));
    }

    public function uploadProof(Request $request, $orderId)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_bayar.required' => 'Waduh, bukti bayarnya jangan lupa diupload ya!',
            'bukti_bayar.image'    => 'Format gambar harus jpeg, png, atau jpg.',
            'bukti_bayar.max'      => 'Ukuran foto terlalu besar, maksimal 2MB ya.',
        ]);

        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        if ($request->hasFile('bukti_bayar')) {
            if ($order->bukti_bayar) {
                Storage::disk('public')->delete($order->bukti_bayar);
            }

            $path = $request->file('bukti_bayar')->store('payment_proofs', 'public');
            
            $order->update([
                'bukti_bayar' => $path,
                'status' => 'menunggu_konfirmasi' 
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dikirim! Mohon tunggu konfirmasi Admin.');
    }
}