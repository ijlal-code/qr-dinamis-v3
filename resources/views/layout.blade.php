<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Dinamis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#0f172a',
                    },
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800">
    <div class="bg-white shadow-sm border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-white font-bold">QR</div>
                <div>
                    <p class="text-sm text-slate-500">Dashboard</p>
                    <p class="font-semibold">QR Dinamis</p>
                </div>
            </div>
            <div class="flex items-center gap-3 text-sm">
                <button id="menu-toggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div id="menu-content" class="hidden md:flex flex-col md:flex-row md:items-center gap-3">
                    @auth
                        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700">{{ auth()->user()->name }}</span>
                        <a href="{{ route('qr.index') }}" class="text-slate-600 hover:text-slate-900">QR</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-slate-900">Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="px-3 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700 w-full text-left md:w-auto md:text-center">Keluar</button>
                        </form>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg bg-slate-900 text-white hover:bg-slate-700">Masuk</a>
                        <a href="{{ route('register') }}" class="px-3 py-2 rounded-lg border border-slate-200 hover:border-slate-300">Daftar</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if(session('status'))
            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-blue-800">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-100 py-4 mt-auto">
        <div class="max-w-6xl mx-auto px-4 text-center text-sm">
            © {{ date('Y') }} Infinitec. All rights reserved.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('menu-toggle');
            const menuContent = document.getElementById('menu-content');

            toggleButton?.addEventListener('click', () => {
                menuContent?.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
