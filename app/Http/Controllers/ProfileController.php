<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User; 
use App\Models\UserVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Ditambahin biar query DB lancar
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil publik user lain
     */
    public function showPublicProfile($id)
    {
        $user = User::findOrFail($id);

        $userProducts = DB::table('barter_items')
                        ->where('user_id', $id)
                        ->latest()
                        ->get();

        return view('profile.public-profile', [
            'user' => $user,
            'userProducts' => $userProducts,
        ]);
    }

    /**
     * Menampilkan profil pribadi (Lemari Virtual)
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        $userProducts = DB::table('barter_items')
                        ->where('user_id', $user->id)
                        ->latest()
                        ->get(); 

        return view('profile.my-profile', [
            'user' => $user,
            'userProducts' => $userProducts,
        ]);
    }

    /**
     * Submit data verifikasi KTP + Selfie + Alamat & No HP
     */
    public function submitVerification(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi ketat biar data nggak asal masuk
        $request->validate([
            'nik' => 'required|digits:16|unique:user_verifications,nik,' . ($user->verification->id ?? 'NULL'),
            'nomor_hp' => 'required|string|min:10|max:15',
            'alamat' => 'required|string|max:500',
            'ktp_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'selfie_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. UPDATE data Alamat & No HP ke tabel 'users'
        $user->update([
            'nomor_hp' => $request->nomor_hp,
            'alamat' => $request->alamat,
        ]);

        // 3. Cleanup storage (Hapus foto lama biar hemat space)
        if ($user->verification) {
            if ($user->verification->ktp_path) {
                Storage::disk('public')->delete($user->verification->ktp_path);
            }
            if ($user->verification->selfie_path) {
                Storage::disk('public')->delete($user->verification->selfie_path);
            }
        }

        // 4. Simpan file foto baru
        $ktpPath = $request->file('ktp_image')->store('id_cards', 'public');
        $selfiePath = $request->file('selfie_image')->store('selfies', 'public');

        // 5. Update atau buat baru data verifikasi
        UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik' => $request->nik,
                'ktp_path' => $ktpPath,
                'selfie_path' => $selfiePath,
                'status' => 'pending', 
                'rejection_reason' => null,  
            ]
        );

        return redirect()->route('profile.my-profile')->with('success', 'Data verifikasi dan alamat berhasil dikirim! Tunggu admin cek ya.');
    }

    /**
     * Update profil lengkap (Nama, Alamat, No HP, Lokasi)
     */
    public function updateFull(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'nomor_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:500',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Eksekusi Update
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'city' => $request->city,
            'district' => $request->district,
            'nomor_hp' => $request->nomor_hp,
            'alamat' => $request->alamat,
            'bio' => $request->bio,
        ]);

        // Kalau password diisi, baru diupdate
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'Profil dan info pengiriman berhasil diperbarui!');
    }

    /**
     * Form Verifikasi
     */
    public function showVerificationForm()
    {
        $user = Auth::user();
        
        if ($user->verification && $user->verification->status == 'verified') {
            return redirect()->route('profile.my-profile')->with('success', 'Akun kamu sudah terverifikasi!');
        }

        return view('profile.verify-ktp', compact('user'));
    }

    /**
     * Default Update dari Laravel Breeze (Opsional, tapi tetep aku simpan)
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

    /**
     * Hapus Akun
     */
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