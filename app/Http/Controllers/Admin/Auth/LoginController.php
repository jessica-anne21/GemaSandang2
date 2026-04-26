<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan view form login admin.
     */
    public function create()
    {
        return view('admin.auth.login');
    }

    /**
     * Menangani percobaan login admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Otentikasi
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Jika kredensial salah
            throw ValidationException::withMessages([
                'email' => 'Kredensial yang dimasukkan tidak cocok.',
            ]);
        }

        // Cek role
        $user = Auth::user();
        if ($user->role !== 'admin') {
            Auth::logout(); 
            throw ValidationException::withMessages([
                'email' => 'Anda tidak memiliki hak akses admin.',
            ]);
        }

        // Kredensial benar DAN rolenya admin
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Menangani logout admin.
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }
}