@extends('layout')

@section('content')
<div class="max-w-xl mx-auto">
    <p class="text-sm text-slate-500">Buat tautan baru</p>
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">QR Dinamis Baru</h1>

    <form action="{{ route('qr.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">URL Tujuan</label>
            <input type="url" name="target_url" class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" placeholder="https://contoh.com" required>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('qr.index') }}" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:border-slate-300">Batal</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-500">Generate QR</button>
        </div>
    </form>
</div>
@endsection
