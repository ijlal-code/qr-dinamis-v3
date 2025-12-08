@extends('layout')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Kelola link dinamis</p>
        <h1 class="text-2xl font-semibold text-slate-900">Daftar QR</h1>
        <p class="text-sm text-slate-500">Total dibuat: {{ $qrs->count() }}</p>
    </div>
    <a href="{{ route('qr.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-white hover:bg-slate-700 w-full justify-center md:w-auto">
        <span class="text-lg">＋</span>
        Buat QR
    </a>
</div>

<div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">#</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">URL Tujuan</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 bg-white">
            @foreach($qrs as $qr)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-sm text-slate-700">{{ $loop->iteration }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $qr->name }}</td>
                <td class="px-6 py-4 font-mono text-sm text-slate-900">{{ $qr->code }}</td>
                <td class="px-6 py-4 text-sm text-slate-700">{{ $qr->target_url }}</td>
                <td class="px-6 py-4">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('qr.show', $qr->id) }}" class="px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-700 hover:border-slate-300">Detail</a>
                        <a href="{{ route('qr.edit', $qr->id) }}" class="px-3 py-2 rounded-lg bg-slate-900 text-sm text-white hover:bg-slate-700">Edit</a>
                        <form action="{{ route('qr.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Hapus QR ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 rounded-lg border border-rose-200 text-sm text-rose-700 hover:bg-rose-50">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="md:hidden space-y-4">
    @foreach($qrs as $qr)
        <div class="relative rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" data-dropdown>
            <div class="flex items-start justify-between gap-3">
                <div class="space-y-1">
                    <p class="text-xs text-slate-500">No: {{ $loop->iteration }}</p>
                    <p class="text-base font-semibold text-slate-900">{{ $qr->name }}</p>
                </div>
                <div class="shrink-0">
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3 py-2 text-white text-sm hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-300" data-dropdown-toggle>
                        Pilihan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mt-3 space-y-1 text-sm text-slate-700">
                <p class="font-mono text-slate-900">Kode: {{ $qr->code }}</p>
                <p class="break-words">URL: {{ $qr->target_url }}</p>
            </div>

            <div class="dropdown-menu hidden absolute right-4 top-full z-20 mt-2 w-48 rounded-xl border border-slate-200 bg-white shadow-lg" role="menu">
                <a href="{{ route('qr.show', $qr->id) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50" role="menuitem">Lihat Detail</a>
                <a href="{{ route('qr.edit', $qr->id) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50" role="menuitem">Edit QR</a>
                <form action="{{ route('qr.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Hapus QR ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-rose-600 hover:bg-rose-50" role="menuitem">Hapus</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        const menus = document.querySelectorAll('.dropdown-menu');

        const closeAll = () => menus.forEach(menu => menu.classList.add('hidden'));

        dropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('[data-dropdown-toggle]');
            const menu = dropdown.querySelector('.dropdown-menu');

            toggle?.addEventListener('click', (event) => {
                event.stopPropagation();
                const willOpen = menu?.classList.contains('hidden');
                closeAll();
                if (willOpen) {
                    menu?.classList.remove('hidden');
                }
            });
        });

        document.addEventListener('click', () => closeAll());
    });
</script>
@endsection
