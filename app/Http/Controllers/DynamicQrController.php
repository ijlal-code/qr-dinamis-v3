<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use QrCode;

class DynamicQrController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

    // Hapus QR
    public function destroy($id)
    {
        $qr = DynamicQr::findOrFail($id);
        $qr->delete();

        return redirect()->route('qr.index')->with('success', 'QR berhasil dihapus.');
    }

    // Download QR sebagai PNG
    public function download($id)
    {
        $qr = DynamicQr::findOrFail($id);
        $png = QrCode::format('png')
            ->size(600)
            ->margin(2)
            ->generate(route('qr.redirect', $qr->code));

        return response($png)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-'.$qr->code.'.png"');
    }
}
