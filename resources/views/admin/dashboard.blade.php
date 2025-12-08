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

<div class="flex items-center justify-end">
    <a href="{{ route('admin.users.index') }}" class="px-5 py-3 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Kelola Pengguna</a>
</div>
@endsection
