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
        <div class="space-y-4 w-full order-2 lg:order-1"> {{-- Di HP form turun ke bawah --}}
            <form action="{{ route('qr.update', $qr->id) }}" method="POST" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm space-y-3">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama QR</label>
                        <input type="text" name="name" value="{{ old('name', $qr->name) }}" class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <input type="hidden" name="target_url" value="{{ $qr->target_url }}">
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 w-full sm:w-auto">Simpan Nama</button>
                </div>
                <p class="text-sm text-slate-500">Nama akan diperbarui tanpa mengubah URL tujuan.</p>
            </form>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-sm text-slate-600 mb-2">URL Tujuan</p>
                <p class="font-medium break-all text-slate-900">{{ $qr->target_url }}</p> {{-- break-all agar URL panjang tidak merusak layout --}}
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('qr.edit', $qr->id) }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Edit URL & Logo</a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center w-full max-w-md mx-auto order-1 lg:order-2">
            {{-- PERBAIKAN: Tambahkan max-w-full dan CSS selector untuk SVG agar responsif --}}
            <div class="relative inline-block p-4 border rounded-lg shadow-md max-w-full [&>svg]:w-full [&>svg]:h-auto" id="qr-svg-wrapper" aria-hidden="true">
                {!! $qrSvg !!}
                @if($logoUrl)
                    <img
                        id="logo-preview"
                        src="{{ $logoUrl }}"
                        alt="Logo QR"
                        class="absolute left-1/2 top-1/2 h-[23%] w-[23%] -translate-x-1/2 -translate-y-1/2 rounded-full object-cover bg-white/80"
                    >
                @endif
            </div>
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-2 w-full">
                <button
                    type="button"
                    id="download-png"
                    class="w-full sm:flex-1 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-center"
                >
                    Download PNG
                </button>
            </div>
            <div class="mt-3 flex flex-col sm:flex-row items-center justify-center gap-2 w-full">
                <a href="{{ $qrLink }}" target="_blank" class="w-full sm:flex-1 px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700 text-center">
                    Buka Link
                </a>
                <button type="button" id="copy-link" data-link="{{ $qrLink }}" class="w-full sm:flex-1 px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300 flex items-center justify-center gap-2">
                    <span aria-hidden="true">📋</span>
                    Salin Link
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Script JS tetap sama --}}
<script>
    // ... (Script JS Anda tetap sama, copy dari kode sebelumnya) ...
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

                ctx.fillStyle = '#ffffff'; // Pastikan background putih
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(image, 0, 0);

                const shouldDrawLogo = logoPreview && !logoPreview.classList.contains('hidden') && logoPreview.src;

                if (shouldDrawLogo) {
                    const logoImage = new Image();
                    logoImage.crossOrigin = 'anonymous';
                    logoImage.onload = () => {
                        const svgRect = qrSvg.getBoundingClientRect();
                        // Gunakan dimensi asli gambar jika tersedia, fallback ke clientRect
                        const renderedWidth = svgRect.width || 320;
                        const renderedHeight = svgRect.height || 320;
                        
                        const scaleX = canvas.width / renderedWidth;
                        const scaleY = canvas.height / renderedHeight;
                        
                        // Hitung ukuran logo relatif terhadap canvas (bukan layar)
                        // Logo di HTML diset sekitar 23% (h-[23%]) dari container
                        const logoSize = canvas.width * 0.23; 
                        
                        const logoX = (canvas.width - logoSize) / 2;
                        const logoY = (canvas.height - logoSize) / 2;

                        ctx.save();
                        ctx.beginPath();
                        ctx.arc(
                            canvas.width / 2,
                            canvas.height / 2,
                            logoSize / 2,
                            0,
                            Math.PI * 2
                        );
                        ctx.closePath();
                        ctx.clip();
                        ctx.drawImage(logoImage, logoX, logoY, logoSize, logoSize);
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