@extends('layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Detail pengguna</p>
        <h1 class="text-2xl font-semibold text-slate-900">{{ $user->name }}</h1>
        <p class="text-sm text-slate-600">{{ $user->email }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Kembali</a>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini beserta semua QR miliknya?')">
            @csrf
            @method('DELETE')
            <button class="px-4 py-2 rounded-lg bg-rose-500 text-white hover:bg-rose-600">Hapus Pengguna</button>
        </form>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3 items-start">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 space-y-3">
        <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">Role</p>
            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->is_admin ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ $user->is_admin ? 'Admin' : 'User' }}</span>
        </div>
        <div>
            <p class="text-sm text-slate-500">Total QR</p>
            <p class="text-3xl font-semibold text-slate-900">{{ $qrs->total() }}</p>
        </div>
    </div>

    <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <div>
                <p class="text-sm text-slate-500">Koleksi QR</p>
                <h2 class="text-lg font-semibold text-slate-900">QR milik {{ $user->name }}</h2>
            </div>
        </div>
        <div class="divide-y divide-slate-200">
            @forelse($qrs as $qr)
                <div class="px-6 py-4 flex flex-col gap-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $qr->name }}</p>
                            <p class="text-sm text-slate-600">Kode: {{ $qr->code }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('qr.redirect', $qr->code) }}" target="_blank" class="px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-sm">Buka</a>
                            <form action="{{ route('admin.qrs.destroy', $qr) }}" method="POST" onsubmit="return confirm('Hapus QR ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-2 rounded-lg bg-rose-500 text-white hover:bg-rose-600 text-sm">Hapus</button>
                            </form>
                        </div>
                    </div>
                    <p class="text-sm text-slate-700 break-all">{{ $qr->target_url }}</p>
                    <p class="text-xs text-slate-500">Scan: {{ $qr->scans_count }}</p>
                </div>
            @empty
                <p class="px-6 py-4 text-sm text-slate-600">Belum ada QR.</p>
            @endforelse
        </div>
        <div class="px-6 py-4">{{ $qrs->links() }}</div>
    </div>
</div>
@endsection
