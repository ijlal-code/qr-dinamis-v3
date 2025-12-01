@extends('layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Ringkasan kendali</p>
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard Admin</h1>
    </div>
    <a href="{{ route('qr.create') }}" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Buat QR</a>
</div>

<div class="grid gap-4 md:grid-cols-3 mb-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-slate-500">Total QR</p>
        <p class="text-3xl font-semibold text-slate-900">{{ $stats['qr_count'] }}</p>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-slate-500">Total Pengguna</p>
        <p class="text-3xl font-semibold text-slate-900">{{ $stats['user_count'] }}</p>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <p class="text-sm text-slate-500">Admin Aktif</p>
        <p class="text-3xl font-semibold text-slate-900">{{ $stats['admin_count'] }}</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2 items-start">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <div>
                <p class="text-sm text-slate-500">Aktivitas terbaru</p>
                <h2 class="text-lg font-semibold text-slate-900">QR Terakhir</h2>
            </div>
        </div>
        <div class="divide-y divide-slate-200">
            @forelse($qrs as $qr)
                <div class="px-6 py-4 flex flex-col gap-1">
                    <div class="flex items-center justify-between text-sm text-slate-600">
                        <span class="font-mono text-slate-900">{{ $qr->code }}</span>
                        <a href="{{ route('qr.show', $qr->id) }}" class="text-indigo-600 hover:text-indigo-500">Detail</a>
                    </div>
                    <p class="text-sm text-slate-700 break-words">{{ $qr->target_url }}</p>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-slate-600">Belum ada data.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Kelola akses</p>
                <h2 class="text-lg font-semibold text-slate-900">Pengguna</h2>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid gap-3 md:grid-cols-2">
                    <input name="name" placeholder="Nama" class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    <input type="email" name="email" placeholder="Email" class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <input type="password" name="password" placeholder="Password" class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    <select name="role" class="w-full rounded-lg border border-slate-200 px-3 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Tambah Pengguna</button>
                </div>
            </form>

            <div class="divide-y divide-slate-200">
                @foreach($users as $user)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-sm text-slate-600">{{ $user->email }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                                    <option value="user" @selected(!$user->is_admin)>User</option>
                                    <option value="admin" @selected($user->is_admin)>Admin</option>
                                </select>
                                <button class="px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-sm">Simpan</button>
                            </form>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-2 rounded-lg bg-rose-500 text-white hover:bg-rose-600 text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
