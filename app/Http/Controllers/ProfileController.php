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

public function index(Request $request): View
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

    // 1. Validasi: Tambahkan selfie_image ke dalam list
    $request->validate([
        'nik' => 'required|digits:16|unique:user_verifications,nik,' . ($user->verification->id ?? 'NULL'),
        'ktp_image' => 'required|image|max:2048',
        'selfie_image' => 'required|image|max:2048', // Tambahkan validasi selfie
    ]);

    // 2. Hapus file lama jika ada (Biar storage Hostinger kamu nggak penuh)
    if ($user->verification) {
        if ($user->verification->ktp_path) {
            Storage::disk('public')->delete($user->verification->ktp_path);
        }
        if ($user->verification->selfie_path) {
            Storage::disk('public')->delete($user->verification->selfie_path);
        }
    }

    // 3. Simpan file baru
    $ktpPath = $request->file('ktp_image')->store('id_cards', 'public');
    $selfiePath = $request->file('selfie_image')->store('selfies', 'public');

    // 4. Update database
    \App\Models\UserVerification::updateOrCreate(
        ['user_id' => $user->id],
        [
            'nik' => $request->nik,
            'ktp_path' => $ktpPath,
            'selfie_path' => $selfiePath, // Masukkan path selfie ke kolom database
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
        'city' => 'required|string|max:100',
        'district' => 'required|string|max:100',
        'username' => 'required|string|max:50|unique:users,username,' . $user->id,
    ]);

    // Pakai update() langsung ke objek user
    $user->update([
        'name' => $request->name,
        'username' => $request->username,
        'city' => $request->city,
        'district' => $request->district,
        'bio' => $request->bio,
    ]);

    // Jika ada ganti password
    if ($request->filled('password')) {
        $user->update(['password' => Hash::make($request->password)]);
    }

    return back()->with('success', 'Profil berhasil diupdate!');
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