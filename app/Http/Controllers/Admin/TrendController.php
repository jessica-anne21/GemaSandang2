<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Customer\NotificationController;

class TrendController extends Controller
{
    public function index()
    {
        $trends = DB::table('trends')->orderBy('created_at', 'desc')->get();
        return view('admin.trends.index', compact('trends'));
    }

    public function importCsv(Request $request)
    {
        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        array_shift($data);

        foreach ($data as $row) {
            $judul = ''; $gambar = ''; $deskripsi = ''; $link_sumber = '';

            // Mapping kolom berdasarkan sumber brand (Zara/Uniqlo) 
            if ($request->sumber == 'Uniqlo') {
                $judul = $row[3] ?? null;
                $gambar = $row[1] ?? null;
                $deskripsi = $row[4] ?? null;
                $link_sumber = $row[0] ?? null; 
            } elseif ($request->sumber == 'Zara') {
                $judul = $row[4] ?? null;
                $deskripsi = $row[5] ?? null;
                $link_sumber = $row[0] ?? null;
                $gambar = $row[0];
                
            }

            // Simpan data dengan status awal 'Draft'
            if (!empty($judul)) {
                DB::table('trends')->insert([
                    'judul'            => $judul,
                    'deskripsi'         => $deskripsi,
                    'gambar'            => $gambar,
                    'sumber'            => $request->sumber,
                    'link_sumber'       => $link_sumber,
                    'skor_popularitas'  => 0,
                    'status'            => 'Draft',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Data Berhasil Diimpor, Silahkan Kurasi!'); 
    }

    // Menampilkan Form Edit/Kurasi
    public function edit($id)
    {
        $trend = DB::table('trends')->where('id', $id)->first();
        return view('admin.trends.edit', compact('trend'));
    }

    public function publish(Request $request, $id)
{
    $request->validate([
        'judul' => 'required',
        'style' => 'required',
        'gambar' => 'required',
    ]);

    // 1. Update data di Database
    DB::table('trends')->where('id', $id)->update([
        'judul'       => $request->judul,
        'deskripsi'   => $request->deskripsi, 
        'style'       => $request->style,
        'warna'       => $request->warna,
        'material'    => $request->material, 
        'gambar'      => $request->gambar,
        'link_sumber' => $request->link_sumber,
        'status'      => 'Published',
        'updated_at'  => now(),
    ]);

    // 2. LOGIC NOTIFIKASI: Cek apakah admin centang 'kirim email'
    if ($request->has('send_email_notif')) {
        $subscribers = \App\Models\User::where('role', 'customer')->get();
        $trendData = DB::table('trends')->where('id', $id)->first();

        foreach ($subscribers as $user) {
            // Panggil Mail kamu di sini
            Mail::to($user->email)->send(new \App\Mail\TrendNotificationMail($trendData));
        }
        $msg = 'Tren berhasil dipublikasikan dan email notifikasi terkirim!';
    } else {
        $msg = 'Tren berhasil dipublikasikan (Tanpa Email).';
    }

    return redirect()->route('admin.trends.index')->with('success', $msg);
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'style' => 'required',
            'warna' => 'required',
        ]);

        DB::table('trends')->where('id', $id)->update([
            'judul'       => $request->judul,
            'deskripsi'   => $request->deskripsi, 
            'style'       => $request->style,
            'warna'       => $request->warna,
            'material'    => $request->material,
            'gambar'      => $request->gambar,      
            'link_sumber' => $request->link_sumber, 
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.trends.index')->with('success', 'Trend Berhasil Diperbarui!');
    }

    public function destroy($id)
    {
        DB::table('trends')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}