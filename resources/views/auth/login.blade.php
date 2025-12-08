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
            <div class="relative">
                <input type="password" name="password" id="login-password" required class="w-full rounded-lg border border-slate-200 px-4 py-3 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 pr-14">
                <button type="button" data-target="login-password" class="absolute inset-y-0 right-3 my-auto text-sm text-slate-600 hover:text-slate-800" aria-label="Tampilkan atau sembunyikan sandi">Tampilkan</button>
            </div>
        </div>
        <div class="flex items-center justify-end text-sm">
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">Buat akun</a>
        </div>
        <button class="w-full px-4 py-3 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Masuk</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButtons = document.querySelectorAll('button[data-target]');

        toggleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);

                if (!input) return;

                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.textContent = isHidden ? 'Sembunyikan' : 'Tampilkan';
            });
        });
    });
</script>
@endsection
