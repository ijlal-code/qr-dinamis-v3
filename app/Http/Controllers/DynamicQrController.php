<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_url' => 'required|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $code = Str::random(8); // kode unik QR
        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $qr = $request->user()->dynamicQrs()->create([
            'code'       => $code,
            'name'       => $validated['name'],
            'target_url' => $validated['target_url'],
            'logo_path'  => $logoPath,
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_url' => 'sometimes|required|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $qr = $this->findUserQr($request->user(), $id);
        $logoPath = $qr->logo_path;

        if ($request->hasFile('logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $qr->update([
            'name'       => $validated['name'],
            'target_url' => $validated['target_url'] ?? $qr->target_url,
            'logo_path'  => $logoPath,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    // Hapus QR
    public function destroy(Request $request, $id)
    {
        $qr = $this->findUserQr($request->user(), $id);

        if ($qr->logo_path) {
            Storage::disk('public')->delete($qr->logo_path);
        }

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
