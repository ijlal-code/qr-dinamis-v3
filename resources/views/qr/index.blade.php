@extends('layout')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-slate-500">Kelola link dinamis</p>
        <h1 class="text-2xl font-semibold text-slate-900">Daftar QR</h1>
    </div>
    <a href="{{ route('qr.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-white hover:bg-slate-700">
        <span class="text-lg">＋</span>
        Buat QR
    </a>
</div>

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">URL Tujuan</th>
                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 bg-white">
            @foreach($qrs as $qr)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $qr->name }}</td>
                <td class="px-6 py-4 font-mono text-sm text-slate-900">{{ $qr->code }}</td>
                <td class="px-6 py-4 text-sm text-slate-700">{{ $qr->target_url }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('qr.show', $qr->id) }}" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Detail</a>
                        <a href="{{ route('qr.edit', $qr->id) }}" class="px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Edit</a>
                        <form action="{{ route('qr.destroy', $qr->id) }}" method="POST" onsubmit="return confirm('Hapus QR ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-2 rounded-lg bg-rose-500 text-white hover:bg-rose-600">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
