<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Trend; 
use App\Models\Product;

class TrendController extends Controller
{
    /**
     * Menampilkan detail tren dan semua komentar di bawahnya
     */
    public function show($id)
{
    $trend = Trend::where('id', $id)
                  ->where('status', 'published') 
                  ->firstOrFail();

    // Ambil semua komentar
    $comments = DB::table('comments')
                ->join('users', 'comments.user_id', '=', 'users.id')
                ->where('trend_id', $id)
                ->select('comments.*', 'users.name', 'users.username', 'users.id as author_id')
                ->orderBy('created_at', 'desc')
                ->get();

    // Ambil produk biasa untuk Cold Start 
    $recommendations = \App\Models\Product::with('category')
                        ->where('stok', '>', 0)
                        ->latest() 
                        ->take(4)
                        ->get();

    return view('customer.trends.show', compact('trend', 'comments', 'recommendations'));
}
    /**
     * Simpan komentar baru ke database
     */
    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'isi_komentar' => 'required|max:1000',
        ]);

        DB::table('comments')->insert([
            'user_id'     => Auth::id(),
            'trend_id'    => $id,
            'isi_komentar' => $request->isi_komentar, 
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Komentar kamu sudah terbit!');
    }

    public function index()
    {
        $trends = Trend::where('status', 'published')
                        ->latest()
                        ->get();

        return view('customer.trends.index', compact('trends'));
    }


}