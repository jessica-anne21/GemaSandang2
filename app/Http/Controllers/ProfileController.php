<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User; 
use App\Models\Product; 
use App\Models\UserVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{

public function showPublicProfile($id)
{
    $user = \App\Models\User::findOrFail($id);

    $userProducts = \Illuminate\Support\Facades\DB::table('barter_items')
                    ->where('user_id', $id)
                    ->latest()
                    ->get();

    return view('profile.public-profile', [
        'user' => $user,
        'userProducts' => $userProducts,
    ]);
}

public function edit(Request $request): View
{
    $user = $request->user();
    
    $userProducts = \Illuminate\Support\Facades\DB::table('barter_items')
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get(); 

    return view('profile.my-profile', [
        'user' => $user,
        'userProducts' => $userProducts,
    ]);
}

    public function submitVerification(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nik' => 'required|digits:16|unique:user_verifications,nik,' . ($user->verification->id ?? 'NULL'),
            'ktp_image' => 'required|image|max:2048',
        ]);

        if ($user->verification && $user->verification->ktp_path) {
            Storage::disk('public')->delete($user->verification->ktp_path);
        }

        $path = $request->file('ktp_image')->store('id_cards', 'public');

        \App\Models\UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => $request->nik,
                'ktp_path' => $path,
                'status' => 'pending', 
                'rejection_reason' => null,  
            ]
        );

        return redirect()->route('profile.my-profile')->with('success', 'Data berhasil diperbarui! Tunggu admin cek lagi ya.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateFull(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:160',
            'password' => 'nullable|min:8',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil kamu berhasil diperbarui!');
    }

    public function showVerificationForm()
    {
        $user = auth()->user();
        
        if ($user->verification && $user->verification->status == 'verified') {
            return redirect()->route('profile.my-profile')->with('success', 'Akun kamu sudah terverifikasi!');
        }

        return view('profile.verify-ktp', compact('user'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}