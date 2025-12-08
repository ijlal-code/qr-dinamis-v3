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
    <style>
        /* Mencegah SVG QR Code merusak layout di HP kecil */
        svg {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 text-slate-800 overflow-x-hidden">
    
    {{-- Navbar Minimalis & Profesional --}}
    <div class="bg-white shadow-sm border-b border-slate-200 w-full sticky top-0 z-50">
        {{-- Menggunakan 'justify-between' agar logo di kiri dan menu di kanan --}}
        <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 py-4 flex items-center justify-between">
            
            {{-- Logo Brand --}}
            <div class="flex items-center gap-2 shrink-0">
                <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-indigo-600 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">QR</div>
                <div>
                    <p class="font-bold text-slate-900 leading-tight">QR Dinamis</p>
                </div>
            </div>

            {{-- Menu Navigasi (Kanan) --}}
            <div class="flex items-center gap-4 text-sm font-medium">
                @auth
                    {{-- Nama User (Hidden di HP sangat kecil agar tidak sempit, opsional) --}}
                    <span class="hidden sm:inline-block text-slate-500 border-r border-slate-200 pr-4">{{ auth()->user()->name }}</span>
                    
                    <a href="{{ route('qr.index') }}" class="text-slate-600 hover:text-indigo-600 transition-colors">
                        Dashboard
                    </a>

                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-slate-600 hover:text-indigo-600 transition-colors">Admin</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        {{-- Tombol Logout Minimalis (Teks Merah) --}}
                        <button class="text-rose-600 hover:text-rose-700 transition-colors">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-indigo-600 font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold hover:bg-slate-800 transition">Daftar</a>
                @endguest
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('status'))
            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-blue-800 text-sm">
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer Full Width --}}
    <footer class="w-full bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 text-center">
            <p class="text-sm text-slate-500 font-medium">© {{ date('Y') }} Infinitec. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>