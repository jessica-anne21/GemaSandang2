<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BarterItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarterController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Ambil barang orang lain (Lemari Teman)
        $barterItems = BarterItem::with('user')
            ->where('user_id', '!=', $user->id) 
            ->where('status', 'available')
            ->latest()
            ->get();

        return view('customer.barter.index', compact('user', 'barterItems'));
    }
}