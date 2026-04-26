<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Http; 

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderByRaw('stok = 0, created_at DESC')
            ->get();

        $query = null; 
        return view('customer.shop', compact('products', 'query'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::with('category')
            ->where('nama_produk', 'LIKE', "%{$query}%")
            ->orderByRaw('stok = 0, created_at DESC') 
            ->get();

        return view('customer.shop', compact('products', 'query'));
    }

    public function showByCategory(Category $category)
    {
        $products = $category->products()
            ->orderByRaw('stok = 0, created_at DESC')
            ->get();

        return view('customer.shop-by-category', compact('products', 'category'));
    }

    public function show(Product $product)
    {
        $recommendations = collect(); 

        try {
            
            $response = Http::timeout(2)->get("http://127.0.0.1:5000/recommend", [
                'id' => $product->id
            ]);

            if ($response->successful()) {
                $recIds = $response->json()['recommendations'];

                $recommendations = Product::whereIn('id', $recIds)
                    ->orderByRaw("FIELD(id, " . implode(',', $recIds) . ")")
                    ->get();
            }
        } catch (\Exception $e) {
            \Log::warning("Mesin Rekomendasi Python mati atau error: " . $e->getMessage());
        }

        return view('customer.product-detail', compact('product', 'recommendations'));
    }
}