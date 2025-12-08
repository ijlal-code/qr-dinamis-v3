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
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-4 w-full">
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
                        <label class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="remove_logo" id="remove-logo" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>Hapus Logo Saat Ini</span>
                        </label>
                        <p class="mt-2 text-xs text-slate-500 break-all">Logo saat ini: {{ $qr->logo_path }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap justify-end gap-2">
                    <a href="{{ route('qr.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300 w-full sm:w-auto text-center">Kembali</a>
                    <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 w-full sm:w-auto">Update</button>
                </div>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col items-center w-full max-w-md mx-auto">
            <p class="text-sm text-slate-600 mb-3">Preview QR Saat Ini</p>
            {{-- PERBAIKAN: CSS Selector responsif untuk SVG --}}
            <div class="relative inline-block p-4 border rounded-lg shadow-md max-w-full [&>svg]:w-full [&>svg]:h-auto" aria-hidden="true" id="qr-svg-wrapper">
                {!! $qrSvg !!}
                <img
                    id="logo-preview"
                    src="{{ $logoUrl }}"
                    data-initial-logo="{{ $logoUrl }}"
                    alt="Preview Logo"
                    class="absolute left-1/2 top-1/2 h-[23%] w-[23%] -translate-x-1/2 -translate-y-1/2 rounded-full object-cover bg-white/80 {{ $logoUrl ? '' : 'hidden' }}"
                >
            </div>
            <div class="mt-4 w-full flex flex-col sm:flex-row sm:items-center sm:justify-center gap-3">
                <button
                    type="button"
                    id="download-png"
                    class="w-full sm:w-auto px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 text-center"
                >
                    Download PNG
                </button>
                <a href="{{ $qrLink }}" target="_blank" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700 text-center">Buka Link</a>
            </div>
        </div>
    </div>
</div>

<script>
    // ... (Gunakan script JS yang sama seperti sebelumnya, update bagian downloadCanvas logic jika diperlukan) ...
    document.addEventListener('DOMContentLoaded', () => {
        const logoInput = document.getElementById('logo-input');
        const logoPreview = document.getElementById('logo-preview');
        const removeLogoCheckbox = document.getElementById('remove-logo');
        const downloadButton = document.getElementById('download-png');
        const qrSvg = document.querySelector('#qr-svg-wrapper svg');

        const initialLogo = logoPreview?.dataset.initialLogo || '';

        const setLogoVisibility = (src) => {
            if (!logoPreview) return;

            if (src) {
                logoPreview.src = src;
                logoPreview.classList.remove('hidden');
            } else {
                logoPreview.src = '';
                logoPreview.classList.add('hidden');
            }
        };

        const renderInitialLogo = () => {
            if (initialLogo && !removeLogoCheckbox?.checked) {
                setLogoVisibility(initialLogo);
            } else {
                setLogoVisibility('');
            }
        };

        const renderSelectedFile = (file) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const result = e.target?.result?.toString() || '';
                setLogoVisibility(result);
            };
            reader.readAsDataURL(file);
        };

        renderInitialLogo();

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', (event) => {
                const file = event.target.files?.[0];

                if (!file) {
                    renderInitialLogo();
                    return;
                }

                if (removeLogoCheckbox) {
                    removeLogoCheckbox.checked = false;
                }

                renderSelectedFile(file);
            });
        }

        if (removeLogoCheckbox) {
            removeLogoCheckbox.addEventListener('change', () => {
                if (removeLogoCheckbox.checked) {
                    setLogoVisibility('');
                    return;
                }

                const file = logoInput?.files?.[0];
                if (file) {
                    renderSelectedFile(file);
                } else {
                    renderInitialLogo();
                }
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

                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(image, 0, 0);

                const shouldDrawLogo = logoPreview && !logoPreview.classList.contains('hidden') && logoPreview.src;

                if (shouldDrawLogo) {
                    const logoImage = new Image();
                    logoImage.crossOrigin = 'anonymous';
                    logoImage.onload = () => {
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