<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;
use Illuminate\Contracts\Auth\Authenticatable;
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
    public function index(Request $request)
    {
        $qrs = $request->user()->dynamicQrs()->latest()->get();
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
            'name' => 'required|string|max:255',
            'target_url' => 'required|url'
        ]);

        $code = Str::random(8); // kode unik QR

        $qr = $request->user()->dynamicQrs()->create([
            'code'       => $code,
            'name'       => $request->name,
            'target_url' => $request->target_url
        ]);

        return redirect()->route('qr.show', $qr->id);
    }

    // Halaman detail QR
    public function show(Request $request, $id)
    {
        $qr = $this->findUserQr($request->user(), $id);
        return view('qr.show', compact('qr'));
    }

    // Edit QR (ubah link tujuan)
    public function edit(Request $request, $id)
    {
        $qr = $this->findUserQr($request->user(), $id);
        return view('qr.edit', compact('qr'));
    }

    // Update QR
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_url' => 'required|url'
        ]);

        $qr = $this->findUserQr($request->user(), $id);
        $qr->update([
            'name'       => $request->name,
            'target_url' => $request->target_url
        ]);

        return redirect()->route('qr.show', $qr->id)->with('success', 'Link berhasil diperbarui!');
    }

    // Hapus QR
    public function destroy(Request $request, $id)
    {
        $qr = $this->findUserQr($request->user(), $id);
        $qr->delete();

        return redirect()->route('qr.index')->with('success', 'QR berhasil dihapus.');
    }

    // Download QR sebagai SVG
    public function download(Request $request, $id)
    {
        $qr = $this->findUserQr($request->user(), $id);
        $svg = QrCode::format('svg')
            ->size(600)
            ->margin(2)
            ->generate(route('qr.redirect', $qr->code));

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="qr-'.$qr->code.'.svg"');
    }

    private function findUserQr(Authenticatable $user, $id): DynamicQr
    {
        return $user->dynamicQrs()->findOrFail($id);
    }
}
