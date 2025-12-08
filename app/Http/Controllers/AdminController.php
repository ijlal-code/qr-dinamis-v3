<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'qr_count' => DynamicQr::count(),
            'user_count' => User::count(),
            'admin_count' => User::where('is_admin', true)->count(),
        ];

        $qrs = DynamicQr::latest()->limit(10)->get();
        $users = User::orderByDesc('is_admin')->orderBy('name')->get();

        return view('admin.dashboard', compact('stats', 'qrs', 'users'));
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount('dynamicQrs')
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $search = $search ?? '';

        return view('admin.users.index', compact('users', 'search'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $qrs = $user->dynamicQrs()->latest()->paginate(10);

        return view('admin.users.show', compact('user', 'qrs'));
    }

    public function destroyUser(Request $request, $id): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $id) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user = User::findOrFail($id);

        foreach ($user->dynamicQrs as $qr) {
            if ($qr->logo_path) {
                Storage::disk('public')->delete($qr->logo_path);
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Pengguna dihapus.');
    }

    public function destroyQr($id): RedirectResponse
    {
        $qr = DynamicQr::findOrFail($id);

        if ($qr->logo_path) {
            Storage::disk('public')->delete($qr->logo_path);
        }

        $qr->delete();

        return back()->with('status', 'QR berhasil dihapus.');
    }
}
