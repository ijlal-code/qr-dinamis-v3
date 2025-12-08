@extends('layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Ringkasan kendali</p>
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard Admin</h1>
    </div>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Kelola Pengguna</a>
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
            <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm">Lihat semua</a>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-slate-600">Admin tidak membuat pengguna secara manual. Gunakan halaman pengguna untuk melihat detail dan menghapus akun bila diperlukan.</p>

            <div class="divide-y divide-slate-200">
                @foreach($users as $user)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                            <p class="text-sm text-slate-600">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700 text-sm">Detail</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
