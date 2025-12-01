<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use QrCode;

class DynamicQrController extends Controller
{
    // Menampilkan daftar QR
    public function index()
    {
        $qrs = DynamicQr::all();
        return view('qr.index', compact('qrs'));
    }

    // Form membuat QR baru
    public function create()
    {
        return view('qr.create');
    }

    // Simpan QR baru
    public function store(Request $request)
    {
        $request->validate([
            'target_url' => 'required|url'
        ]);

        $code = Str::random(8); // kode unik QR

        $qr = DynamicQr::create([
            'code'       => $code,
            'target_url' => $request->target_url
        ]);

        return redirect()->route('qr.show', $qr->id);
    }

    // Halaman detail QR
    public function show($id)
    {
        $qr = DynamicQr::findOrFail($id);
        return view('qr.show', compact('qr'));
    }

    // Edit QR (ubah link tujuan)
    public function edit($id)
    {
        $qr = DynamicQr::findOrFail($id);
        return view('qr.edit', compact('qr'));
    }

    // Update QR
    public function update(Request $request, $id)
    {
        $request->validate([
            'target_url' => 'required|url'
        ]);

        $qr = DynamicQr::findOrFail($id);
        $qr->update([
            'target_url' => $request->target_url
        ]);

        return redirect()->route('qr.show', $qr->id)->with('success', 'Link berhasil diperbarui!');
    }
}
