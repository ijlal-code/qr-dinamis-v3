@extends('layout')

@section('content')
<div class="max-w-xl mx-auto w-full">
    <p class="text-sm text-slate-500">Perbarui tujuan</p>
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">Edit QR {{ $qr->code }}</h1>

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

        <div class="flex justify-end gap-2">
            <a href="{{ route('qr.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Batal</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Update</button>
        </div>
    </form>
</div>
@endsection
