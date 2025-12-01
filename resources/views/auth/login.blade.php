@extends('layout')

@section('content')
<div class="max-w-md mx-auto">
    <p class="text-sm text-slate-500">Selamat datang kembali</p>
    <h1 class="text-2xl font-semibold text-slate-900 mb-6">Masuk</h1>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
            <input type="password" name="password" required class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600">
                Ingat saya
            </label>
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">Buat akun</a>
        </div>
        <button class="w-full px-4 py-3 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Masuk</button>
    </form>
</div>
@endsection
