@extends('layout')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar QR</h1>
        <p class="text-slate-500 text-sm mt-1">Total dibuat: <span class="font-semibold text-slate-700">{{ $qrs->count() }}</span></p>
    </div>
    <a href="{{ route('qr.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-white font-medium hover:bg-indigo-700 transition shadow-sm w-full justify-center md:w-auto">
        <span class="text-xl leading-none">＋</span>
        Buat QR Baru
    </a>
</div>

{{-- Tampilan Desktop (Tabel) --}}
<div class="hidden md:block overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50/50">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">No</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nama</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Kode</th>
                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">URL Tujuan</th>
                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 bg-white">
            @forelse($qrs as $qr)
            <tr class="hover:bg-slate-50/80 transition">
                <td class="px-6 py-4 text-sm text-slate-500">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $qr->name }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10 font-mono">{{ $qr->code }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">{{ $qr->target_url }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('qr.show', $qr->id) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-medium text-slate-700 hover:border-indigo-300 hover:text-indigo-600 transition">Detail</a>
                        <a href="{{ route('qr.edit', $qr->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-900 text-xs font-medium text-white hover:bg-slate-700 transition">Edit</a>
                        <form action="{{ route('qr.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Hapus QR ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 text-xs font-medium text-rose-600 hover:bg-rose-50 transition">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada QR Code yang dibuat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Tampilan Mobile (Card dengan Dropdown FIX) --}}
<div class="md:hidden space-y-4">
    @foreach($qrs as $qr)
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">#{{ $loop->iteration }}</p>
                    <h3 class="text-lg font-bold text-slate-900">{{ $qr->name }}</h3>
                </div>
                
                {{-- WRAPPER DROPDOWN (PENTING: Class 'relative' ada di sini, bukan di parent card) --}}
                <div class="relative" data-dropdown>
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-slate-700 text-xs font-medium hover:bg-slate-200 transition" data-dropdown-toggle>
                        Pilihan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu (Akan muncul pas di bawah tombol di atas karena parent-nya relative) --}}
                    <div class="dropdown-menu hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden origin-top-right transform transition-all">
                        <a href="{{ route('qr.show', $qr->id) }}" class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 border-b border-slate-50">
                            Lihat Detail
                        </a>
                        <a href="{{ route('qr.edit', $qr->id) }}" class="block px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 border-b border-slate-50">
                            Edit QR
                        </a>
                        <form action="{{ route('qr.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Hapus QR ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                {{-- END WRAPPER DROPDOWN --}}
            </div>

            <div class="mt-4 space-y-2 border-t border-slate-100 pt-3">
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <span class="text-xs uppercase font-bold text-slate-400 w-10">Kode</span>
                    <span class="font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-100 text-slate-800">{{ $qr->code }}</span>
                </div>
                <div class="flex items-start gap-2 text-sm text-slate-600">
                    <span class="text-xs uppercase font-bold text-slate-400 w-10 mt-0.5">URL</span>
                    <p class="break-all text-indigo-600 leading-tight line-clamp-2">{{ $qr->target_url }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Seleksi wrapper dropdown (div dengan atribut data-dropdown)
        const dropdownWrappers = document.querySelectorAll('[data-dropdown]');
        
        // Fungsi helper untuk menutup semua menu
        const closeAllDropdowns = () => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        };

        dropdownWrappers.forEach(wrapper => {
            const toggleBtn = wrapper.querySelector('[data-dropdown-toggle]');
            const menu = wrapper.querySelector('.dropdown-menu');

            toggleBtn?.addEventListener('click', (e) => {
                e.stopPropagation(); // Mencegah event klik tembus ke document
                
                const isHidden = menu.classList.contains('hidden');
                
                // 1. Tutup semua dropdown lain dulu agar tidak menumpuk
                closeAllDropdowns();

                // 2. Jika tadi tertutup, sekarang buka
                if (isHidden) {
                    menu.classList.remove('hidden');
                }
            });
        });

        // Klik di area kosong (document) akan menutup semua dropdown
        document.addEventListener('click', () => {
            closeAllDropdowns();
        });
    });
</script>
@endsection