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
                    <input type="file" name="logo" id="logo-input" accept="image/*" class="w-full rounded-lg border border-slate-200 px-4 py-2 bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
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
            <div class="relative inline-block p-4 border rounded-lg shadow-md" aria-hidden="true" id="qr-svg-wrapper">
                {!! $qrSvg !!}
                <img
                    id="logo-preview"
                    src="{{ $logoUrl }}"
                    data-initial-logo="{{ $logoUrl }}"
                    alt="Preview Logo"
                    class="absolute left-1/2 top-1/2 h-16 w-16 -translate-x-1/2 -translate-y-1/2 rounded-full object-contain bg-white/80 {{ $logoUrl ? '' : 'hidden' }}"
                >
            </div>
            <div class="mt-4 w-full flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3">
                <button
                    type="button"
                    id="download-png"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-center"
                >
                    Download PNG
                </button>
                <a href="{{ $qrLink }}" target="_blank" class="px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700 text-center">Buka Link</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const logoInput = document.getElementById('logo-input');
        const logoPreview = document.getElementById('logo-preview');
        const downloadButton = document.getElementById('download-png');
        const qrSvg = document.querySelector('#qr-svg-wrapper svg');

        const initialLogo = logoPreview?.dataset.initialLogo || '';
        if (logoPreview && initialLogo) {
            logoPreview.src = initialLogo;
            logoPreview.classList.remove('hidden');
        }

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', (event) => {
                const file = event.target.files?.[0];

                if (!file) {
                    if (initialLogo) {
                        logoPreview.src = initialLogo;
                        logoPreview.classList.remove('hidden');
                    } else {
                        logoPreview.classList.add('hidden');
                        logoPreview.src = '';
                    }
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    logoPreview.src = e.target?.result || '';
                    logoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
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

                const shouldDrawLogo = logoPreview && !logoPreview.classList.contains('hidden') && logoPreview.src;

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

                        ctx.drawImage(logoImage, logoX, logoY, logoWidth, logoHeight);
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
