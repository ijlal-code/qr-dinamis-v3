@extends('layout')

@section('content')
@php
    $qrLink = route('qr.redirect', $qr->code);
    $qrPng = base64_encode(QrCode::format('png')->size(320)->margin(2)->generate($qrLink));
@endphp

<div class="grid gap-6 md:grid-cols-2 items-start">
    <div class="space-y-4">
        <p class="text-sm text-slate-500">Kode: {{ $qr->code }}</p>
        <h1 class="text-3xl font-semibold text-slate-900">QR Dinamis</h1>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-600 mb-2">URL Tujuan</p>
            <p class="font-medium break-words text-slate-900">{{ $qr->target_url }}</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('qr.edit', $qr->id) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Edit URL</a>
            <a href="{{ route('qr.download', $qr->id) }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Unduh PNG</a>
            <a href="{{ $qrLink }}" target="_blank" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Buka Link</a>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center">
        <img src="data:image/png;base64,{{ $qrPng }}" alt="QR" class="w-60 h-60 object-contain">
        <p class="mt-4 text-sm text-slate-600">Scan untuk menuju:<br><span class="font-medium text-slate-900">{{ $qrLink }}</span></p>
    </div>
</div>
@endsection
