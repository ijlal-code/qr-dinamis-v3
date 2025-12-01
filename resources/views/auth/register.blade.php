@extends('layout')

@section('content')
<div class="max-w-md mx-auto">
    <p class="text-sm text-slate-500">Mulai sekarang</p>
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">Daftar Akun</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
            <input type="password" name="password" required class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="flex items-center justify-between text-sm">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">Sudah punya akun?</a>
        </div>
        <button class="w-full px-4 py-3 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Daftar</button>
    </form>
</div>
@endsection
