@extends('layout')

@section('content')
@php
    $qrLink = route('qr.redirect', $qr->code);
    $qrSvg = QrCode::format('svg')->size(320)->margin(2)->generate($qrLink);
@endphp

<div class="grid gap-6 md:grid-cols-2 items-start">
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm text-slate-500">Kode: {{ $qr->code }}</p>
                <h1 class="text-3xl font-semibold text-slate-900">QR Dinamis</h1>
            </div>
            <a href="{{ route('qr.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Kembali ke Dashboard</a>
        </div>

        <form action="{{ route('qr.update', $qr->id) }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm space-y-3">
            @csrf
            @method('PUT')
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nama QR</label>
                    <input type="text" name="name" value="{{ old('name', $qr->name) }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                </div>
                <input type="hidden" name="target_url" value="{{ $qr->target_url }}">
                <button class="px-4 py-2 mt-6 h-fit rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Simpan Nama</button>
            </div>
            <p class="text-sm text-slate-500">Nama akan diperbarui tanpa mengubah URL tujuan.</p>
        </form>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-slate-600 mb-2">URL Tujuan</p>
            <p class="font-medium break-words text-slate-900">{{ $qr->target_url }}</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('qr.edit', $qr->id) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Edit URL</a>
            <button type="button" id="download-qr-png" data-filename="qr-{{ $qr->code }}.png" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Unduh PNG</button>
            <a href="{{ $qrLink }}" target="_blank" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Buka Link</a>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center">
        <div id="qr-svg-wrapper" class="w-60 h-60" aria-hidden="true">{!! $qrSvg !!}</div>
        <p class="mt-4 text-sm text-slate-600">Scan untuk menuju:<br><span class="font-medium text-slate-900">{{ $qrLink }}</span></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const downloadButton = document.getElementById('download-qr-png');
        const svgWrapper = document.getElementById('qr-svg-wrapper');

        if (!downloadButton || !svgWrapper) return;

        downloadButton.addEventListener('click', () => {
            const svgElement = svgWrapper.querySelector('svg');
            if (!svgElement) return;

            const serializer = new XMLSerializer();
            const svgString = serializer.serializeToString(svgElement);
            const svgBlob = new Blob([svgString], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(svgBlob);

            const image = new Image();
            image.onload = () => {
                const canvas = document.createElement('canvas');
                const width = svgElement.viewBox?.baseVal?.width || svgElement.width?.baseVal?.value || 600;
                const height = svgElement.viewBox?.baseVal?.height || svgElement.height?.baseVal?.value || width;

                canvas.width = width;
                canvas.height = height;

                const context = canvas.getContext('2d');
                context.drawImage(image, 0, 0, width, height);

                const pngUrl = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.href = pngUrl;
                link.download = downloadButton.dataset.filename || 'qr-code.png';
                document.body.appendChild(link);
                link.click();
                link.remove();

                URL.revokeObjectURL(url);
            };

            image.onerror = () => URL.revokeObjectURL(url);
            image.src = url;
        });
    });
</script>
@endsection
