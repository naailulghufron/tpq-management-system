<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $description ?? 'TPQ Management System - pendidikan Al-Quran modern, hangat, dan terarah.' }}">

        <title>{{ isset($title) ? $title.' - ' : '' }}TPQ Management System</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 text-gray-900 antialiased">
        <div class="min-h-screen">
            <header class="sticky top-0 z-40 border-b border-emerald-100 bg-white/95 backdrop-blur">
                <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="Navigasi utama">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-lg bg-emerald-700 text-lg font-semibold text-white">TP</span>
                        <span class="min-w-0">
                            <span class="block text-sm font-semibold leading-5 text-gray-950">TPQ Management</span>
                            <span class="block text-xs leading-4 text-emerald-700">Belajar Qur'an dengan tertata</span>
                        </span>
                    </a>

                    <details class="relative md:hidden">
                        <summary class="list-none rounded-md border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 marker:hidden">
                            Menu
                        </summary>
                        <div class="absolute right-0 mt-2 w-64 rounded-lg border border-gray-200 bg-white p-2 shadow-lg">
                            @foreach ([
                                ['Home', 'home'],
                                ['Profil', 'profile'],
                                ['Program', 'programs'],
                                ['Blog', 'blog'],
                                ['Galeri', 'gallery'],
                                ['Pengumuman', 'announcements'],
                                ['Kontak', 'contact'],
                                ['Daftar', 'registration'],
                            ] as [$label, $route])
                                <a href="{{ route($route) }}" class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-800">{{ $label }}</a>
                            @endforeach
                        </div>
                    </details>

                    <div class="hidden items-center gap-1 md:flex">
                        @foreach ([
                            ['Home', 'home'],
                            ['Profil', 'profile'],
                            ['Program', 'programs'],
                            ['Blog', 'blog'],
                            ['Galeri', 'gallery'],
                            ['Pengumuman', 'announcements'],
                            ['Kontak', 'contact'],
                        ] as [$label, $route])
                            <a href="{{ route($route) }}" class="rounded-md px-3 py-2 text-sm font-medium {{ request()->routeIs($route) ? 'bg-emerald-50 text-emerald-800' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-950' }}">{{ $label }}</a>
                        @endforeach
                        <a href="{{ route('registration') }}" class="ml-2 rounded-md bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800">Pendaftaran</a>
                    </div>
                </nav>
            </header>

            <main>
                {{ $slot }}
            </main>

            <footer class="border-t border-gray-200 bg-white">
                <div class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 md:grid-cols-3 lg:px-8">
                    <div>
                        <p class="text-sm font-semibold text-gray-950">TPQ Management System</p>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Platform informasi publik untuk pendidikan Al-Qur'an yang rapi, amanah, dan mudah diakses dari HP.</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-950">Menu</p>
                        <div class="mt-2 grid gap-1 text-sm text-gray-600">
                            <a href="{{ route('programs') }}" class="hover:text-emerald-700">Program Pendidikan</a>
                            <a href="{{ route('announcements') }}" class="hover:text-emerald-700">Pengumuman</a>
                            <a href="{{ route('registration') }}" class="hover:text-emerald-700">Pendaftaran Santri</a>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-950">Kontak</p>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Silakan hubungi pengurus TPQ untuk jadwal kunjungan, konsultasi program, dan informasi pendaftaran.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
