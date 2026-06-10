<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'So Fresh Laundry') }}</title>
    <!-- LOGO KUSTOM SO FRESH LAUNDRY UNTUK TAB BROWSER -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='aquaGrad' x1='0%' y1='0%' x2='100%' y2='100%'><stop offset='0%' stop-color='%230ea5e9'/><stop offset='100%' stop-color='%2310b981'/></linearGradient></defs><rect width='100' height='100' rx='25' fill='url(%23aquaGrad)'/><circle cx='40' cy='45' r='16' fill='white' opacity='0.2'/><circle cx='52' cy='52' r='22' fill='none' stroke='white' stroke-width='6' stroke-linecap='round'/><circle cx='70' cy='35' r='8' fill='none' stroke='white' stroke-width='3'/><path d='M 36 50 Q 52 65 68 50' fill='none' stroke='white' stroke-width='5' stroke-linecap='round'/><circle cx='45' cy='42' r='3' fill='white'/><circle cx='59' cy='42' r='3' fill='white'/></svg>">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-100" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-gray-100 flex flex-col transform md:translate-x-0 md:static transition-transform duration-300 ease-in-out flex-shrink-0">

            <div
                class="h-16 flex items-center justify-between px-6 bg-gray-950 font-bold text-lg tracking-wider text-blue-400">
                <!-- WRAPPER LOGO & NAMA LAUNDRY DI ATAS SIDEBAR -->
                <div class="flex items-center gap-3 px-3 py-4 border-b border-gray-100/50 mb-4 select-none">
                    <!-- Ikon Mini Gelembung Sabun dengan Gradasi Segar -->
                    <div
                        class="flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-sky-400 to-emerald-400 text-white text-lg shadow-md shadow-sky-100">
                        🫧
                    </div>
                    <!-- Teks Identitas Aplikasi -->
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-sky-200 uppercase tracking-widest mt-1">
                            So Fresh Laundry
                        </span>
                    </div>
                </div>
                <button @click="sidebarOpen = false"
                    class="text-gray-400 hover:text-white md:hidden focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>

                <a href="{{ route('transactions.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('transactions.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                    <span class="mr-3">💼</span> Transaksi Laundry
                </a>

                <a href="{{ route('customers.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                    <span class="mr-3">👥</span> Pelanggan
                </a>

                <a href="{{ route('services.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('services.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                    <span class="mr-3">🧺</span> Layanan Laundry
                </a>

                <a href="{{ route('aromas.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('aromas.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                    <span class="mr-3">✨</span> Pilihan Aroma
                </a>

                <a href="{{ route('promos.index') }}"
                    class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('promos.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                    <span class="mr-3">🎟️</span> Diskon & Promo
                </a>

                @if (auth()->user()->role === 'admin')
                    <div class="pt-4 border-t border-gray-700/50 my-3">
                        <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider">Panel Owner / Admin
                        </p>
                    </div>

                    <a href="{{ route('reports.index') }}"
                        class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                        <span class="mr-3">📈</span> Laporan Keuangan
                    </a>

                    <a href="{{ route('users.index') }}"
                        class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                        <span class="mr-3">🔑</span> Manajemen User
                    </a>

                    <a href="{{ route('backups.index') }}"
                        class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition {{ request()->routeIs('backups.*') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-gray-100' }}">
                        <span class="mr-3">💾</span> Backups & Restore
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-800 bg-gray-950 text-xs text-gray-500">
                Masuk sebagai: <span class="text-gray-300 font-medium block">{{ Auth::user()->name }}</span>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black opacity-50 md:hidden"
            x-transition:enter="transition ease-out duration-300" x-transition:leave="transition ease-in duration-300">
        </div>

        <div class="flex flex-col flex-1 overflow-hidden w-full">

            <!-- HEADER: Background Gelap dengan Teks Putih -->
            <header
                class="h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-6 flex-shrink-0 text-white shadow-md">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-slate-300 hover:text-white focus:outline-none md:hidden">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Teks Putih -->
                    <div class="text-sm font-medium text-slate-200 hidden sm:block">
                        Sistem Informasi Manajemen Laundry
                    </div>
                </div>

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <!-- Tombol User dengan background transparan dan teks putih -->
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-200 bg-slate-700 hover:text-white hover:bg-slate-600 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50">
                @if (isset($header))
                    <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                        {{ $header }}
                    </div>
                @endif

                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>

    </div>
</body>

</html>
