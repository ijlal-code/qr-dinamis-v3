<?php

namespace App\Http\Controllers;

use App\Models\DynamicQr;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
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

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => $data['role'] === 'admin',
        ]);

        return back()->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function updateRole(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        $user->update([
            'is_admin' => $request->role === 'admin',
        ]);

        return back()->with('status', 'Role pengguna diperbarui.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return back()->with('status', 'Pengguna dihapus.');
    }
}
