<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Customer\TrendController;
use App\Models\Trend; 

class HomeController extends Controller
{

    public function index()
    {
        $products = Product::latest()->take(8)->get();
        
        $trends = Trend::where('status', 'published') 
                        ->latest()
                        ->take(5)
                        ->get();

        return view('customer.home', compact('products', 'trends'));
    }

    /**
     * Menampilkan halaman 'about'.
     */
    public function about()
    {
        return view('customer.about');
    }

    /**
     * Menampilkan halaman kontak.
     */
    public function contact()
    {
        return view('customer.contact');
    }

    public function likeTrend($id)
{
    $userId = auth()->id();
    
    // Cek apakah user sudah pernah LIKE di tabel interactions
    $existingLike = DB::table('trend_interactions')
        ->where('user_id', $userId)
        ->where('trend_id', $id)
        ->first();

    if ($existingLike) {
        // UNLIKE: Hapus data dari trend_interactions
        DB::table('trend_interactions')->where('id', $existingLike->id)->delete();
        DB::table('trends')->where('id', $id)->decrement('skor_popularitas');
        $status = 'unliked';
    } else {
        // LIKE: Masukkan data ke trend_interactions
        DB::table('trend_interactions')->insert([
            'user_id' => $userId,
            'trend_id' => $id,
            'created_at' => now()
        ]);
        DB::table('trends')->where('id', $id)->increment('skor_popularitas');
        $status = 'liked';
    }

    $newScore = DB::table('trends')->where('id', $id)->value('skor_popularitas');

    return response()->json([
        'success' => true,
        'status' => $status,
        'new_score' => $newScore
    ]);
}
}