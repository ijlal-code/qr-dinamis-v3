@extends('layout')

@section('content')
@php
    $qrLink = route('qr.redirect', $qr->code);
    $logoPath = $qr->logo_path ? storage_path('app/public/' . $qr->logo_path) : null;
    $qrBuilder = QrCode::format('svg')->size(320)->margin(2);
    $logoUrl = $qr->logo_path ? asset('storage/' . $qr->logo_path) : null;

    if ($logoPath && file_exists($logoPath)) {
        $qrBuilder = $qrBuilder
            ->errorCorrection('H')
            ->merge($logoPath, 0.3, true);
    }

    $qrSvg = $qrBuilder->generate($qrLink);
@endphp

<div class="w-full max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-slate-500">Kode: {{ $qr->code }}</p>
            <h1 class="text-3xl font-semibold text-slate-900">QR Dinamis</h1>
        </div>
        <a href="{{ route('qr.index') }}" class="self-start px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Kembali ke Dashboard</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 items-start">
        <div class="space-y-4 w-full">
            <form action="{{ route('qr.update', $qr->id) }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm space-y-3">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama QR</label>
                        <input type="text" name="name" value="{{ old('name', $qr->name) }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <input type="hidden" name="target_url" value="{{ $qr->target_url }}">
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Simpan Nama</button>
                </div>
                <p class="text-sm text-slate-500">Nama akan diperbarui tanpa mengubah URL tujuan.</p>
            </form>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-slate-600 mb-2">URL Tujuan</p>
                <p class="font-medium break-words text-slate-900">{{ $qr->target_url }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('qr.edit', $qr->id) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Edit URL</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center w-full max-w-md mx-auto">
            <div class="relative inline-block p-4 border rounded-lg shadow-md" id="qr-svg-wrapper" aria-hidden="true">
                {!! $qrSvg !!}
                @if($logoUrl)
                    <img
                        id="logo-preview"
                        src="{{ $logoUrl }}"
                        alt="Logo QR"
                        class="absolute left-1/2 top-1/2 h-16 w-16 -translate-x-1/2 -translate-y-1/2 rounded-full object-contain bg-white/80"
                    >
                @endif
            </div>
            <div class="mt-4 flex items-center justify-center gap-2 w-full">
                <button
                    type="button"
                    id="download-png"
                    class="flex-1 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-center"
                >
                    Download PNG
                </button>
            </div>
            <div class="mt-3 flex items-center justify-center gap-2 w-full">
                <a href="{{ $qrLink }}" target="_blank" class="flex-1 px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700 text-center">
                    Buka Link
                </a>
                <button type="button" id="copy-link" data-link="{{ $qrLink }}" class="flex-1 px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300 flex items-center justify-center gap-2">
                    <span aria-hidden="true">📋</span>
                    Salin Link
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const copyButton = document.getElementById('copy-link');
        const downloadButton = document.getElementById('download-png');
        const qrSvg = document.querySelector('#qr-svg-wrapper svg');
        const logoPreview = document.getElementById('logo-preview');

        if (copyButton) {
            const originalLabel = copyButton.innerHTML;

            copyButton.addEventListener('click', () => {
                const link = copyButton.dataset.link;

                if (!link) return;

                navigator.clipboard?.writeText(link)
                    .then(() => {
                        copyButton.innerHTML = '✔️ Disalin!';
                        setTimeout(() => copyButton.innerHTML = originalLabel, 1500);
                    })
                    .catch(() => alert('Gagal menyalin link'));
            });
        }

        const drawQrToCanvas = () => new Promise((resolve) => {
            if (!qrSvg) {
                resolve(null);
                return;
            }

            const serializer = new XMLSerializer();
            const svgData = serializer.serializeToString(qrSvg);
            const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
            const url = URL.createObjectURL(svgBlob);

            const image = new Image();
            image.crossOrigin = 'anonymous';

            image.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = image.width;
                canvas.height = image.height;

                const ctx = canvas.getContext('2d');

                if (!ctx) {
                    URL.revokeObjectURL(url);
                    resolve(null);
                    return;
                }

                ctx.drawImage(image, 0, 0);

                const shouldDrawLogo = logoPreview && logoPreview.src;

                if (shouldDrawLogo) {
                    const logoImage = new Image();
                    logoImage.crossOrigin = 'anonymous';
                    logoImage.onload = () => {
                        const svgRect = qrSvg.getBoundingClientRect();
                        const logoRect = logoPreview.getBoundingClientRect();
                        const scaleX = canvas.width / svgRect.width;
                        const scaleY = canvas.height / svgRect.height;
                        const logoWidth = logoRect.width * scaleX;
                        const logoHeight = logoRect.height * scaleY;
                        const logoX = (canvas.width - logoWidth) / 2;
                        const logoY = (canvas.height - logoHeight) / 2;

                        ctx.save();
                        ctx.beginPath();
                        ctx.arc(
                            logoX + logoWidth / 2,
                            logoY + logoHeight / 2,
                            Math.min(logoWidth, logoHeight) / 2,
                            0,
                            Math.PI * 2
                        );
                        ctx.closePath();
                        ctx.clip();
                        ctx.drawImage(logoImage, logoX, logoY, logoWidth, logoHeight);
                        ctx.restore();

                        URL.revokeObjectURL(url);
                        resolve(canvas);
                    };
                    logoImage.onerror = () => {
                        URL.revokeObjectURL(url);
                        resolve(canvas);
                    };
                    logoImage.src = logoPreview.src;
                } else {
                    URL.revokeObjectURL(url);
                    resolve(canvas);
                }
            };

            image.onerror = () => {
                URL.revokeObjectURL(url);
                resolve(null);
            };

            image.src = url;
        });

        const downloadSvgAsPng = async () => {
            const canvas = await drawQrToCanvas();
            if (!canvas) return;

            canvas.toBlob((blob) => {
                if (!blob) return;

                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'qr-{{ $qr->code }}.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(link.href);
            });
        };

        downloadButton?.addEventListener('click', downloadSvgAsPng);
    });
</script>
@endsection
