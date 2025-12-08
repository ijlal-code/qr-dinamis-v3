@extends('layout')

@section('content')
@php
    $qrLink = route('qr.redirect', $qr->code);
    $logoPath = $qr->logo_path ? storage_path('app/public/' . $qr->logo_path) : null;
    $qrBuilder = QrCode::format('svg')->size(320)->margin(2);

    if ($logoPath && file_exists($logoPath)) {
        $qrBuilder = $qrBuilder->merge($logoPath, 0.3, true);
    }

    $qrSvg = $qrBuilder->generate($qrLink);
@endphp

<div class="w-full max-w-6xl mx-auto space-y-4">
    <p class="text-sm text-slate-500">Perbarui tujuan</p>
    <h1 class="text-2xl font-semibold text-slate-900">Edit QR {{ $qr->code }}</h1>

    <div class="grid gap-6 lg:grid-cols-2 items-start">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <form action="{{ route('qr.update', $qr->id) }}" method="POST" class="space-y-4" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama QR</label>
                    <input type="text" name="name" class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" value="{{ $qr->name }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">URL Baru</label>
                    <input type="url" name="target_url" class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" value="{{ $qr->target_url }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Logo (opsional)</label>
                    <input type="file" name="logo" accept="image/*" class="w-full rounded-lg border border-slate-200 px-4 py-2 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                    @if($qr->logo_path)
                        <p class="mt-2 text-xs text-slate-500 break-all">Logo saat ini: {{ $qr->logo_path }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap justify-end gap-2">
                    <a href="{{ route('qr.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Kembali ke Dashboard</a>
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Update</button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center">
            <p class="text-sm text-slate-600 mb-3">Preview QR Saat Ini</p>
            <div class="inline-block p-4 border rounded-lg shadow-md" aria-hidden="true">{!! $qrSvg !!}</div>
            <a href="{{ $qrLink }}" target="_blank" class="mt-4 px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Buka Link</a>
        </div>
    </div>
</div>
@endsection
