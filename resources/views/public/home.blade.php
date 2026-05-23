<x-public-layout title="Home">
    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 sm:py-14 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-16">
            <div class="flex flex-col justify-center">
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">TPQ Modern dan Amanah</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-normal text-gray-950 sm:text-5xl">Pendidikan Al-Qur'an yang rapi, hangat, dan mudah dipantau.</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-gray-600">Kelola informasi program, pengumuman, galeri, dan pendaftaran santri dengan tampilan ringan untuk keluarga santri dan masyarakat.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('registration') }}" class="rounded-md bg-emerald-700 px-5 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-emerald-800">Daftar Santri</a>
                    <a href="{{ route('programs') }}" class="rounded-md border border-emerald-200 px-5 py-3 text-center text-sm font-semibold text-emerald-800 hover:bg-emerald-50">Lihat Program</a>
                </div>
            </div>
            <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-5">
                <div class="rounded-lg bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-emerald-800">Nilai Utama</p>
                    <div class="mt-4 grid gap-3">
                        @foreach (['Adab sebelum ilmu', 'Bacaan Qur’an bertahap', 'Pembinaan karakter Islami', 'Komunikasi wali santri'] as $item)
                            <div class="flex items-center gap-3 rounded-md bg-gray-50 p-3">
                                <span class="size-2 rounded-full bg-yellow-500"></span>
                                <span class="text-sm font-medium text-gray-700">{{ $item }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-emerald-700">Program</p>
                <h2 class="mt-1 text-2xl font-semibold text-gray-950">Program Pendidikan</h2>
            </div>
            <a href="{{ route('programs') }}" class="text-sm font-semibold text-emerald-700">Semua program</a>
        </div>
        <div class="mt-5 grid gap-4 md:grid-cols-3">
            @forelse ($programs as $program)
                <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <h3 class="font-semibold text-gray-950">{{ $program->name }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm leading-6 text-gray-600">{{ $program->description ?: 'Program pendidikan TPQ yang disusun bertahap sesuai kebutuhan santri.' }}</p>
                </article>
            @empty
                @include('public.partials.empty-state', ['title' => 'Program belum tersedia', 'message' => 'Data program akan tampil setelah dipublikasikan dari admin.'])
            @endforelse
        </div>
    </section>

    <section class="bg-gray-100">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <h2 class="text-xl font-semibold text-gray-950">Pengumuman terbaru</h2>
                <div class="mt-4 grid gap-3">
                    @forelse ($announcements as $announcement)
                        <article class="rounded-lg border border-gray-200 bg-white p-4">
                            <h3 class="font-semibold text-gray-950">{{ $announcement->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ str($announcement->content)->stripTags()->limit(120) }}</p>
                        </article>
                    @empty
                        @include('public.partials.empty-state', ['title' => 'Belum ada pengumuman', 'message' => 'Informasi terbaru TPQ akan muncul di sini.'])
                    @endforelse
                </div>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-950">Blog terbaru</h2>
                <div class="mt-4 grid gap-3">
                    @forelse ($posts as $post)
                        <article class="rounded-lg border border-gray-200 bg-white p-4">
                            <h3 class="font-semibold text-gray-950">{{ $post->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $post->excerpt ?: str($post->content)->stripTags()->limit(120) }}</p>
                        </article>
                    @empty
                        @include('public.partials.empty-state', ['title' => 'Belum ada artikel', 'message' => 'Artikel dan kabar TPQ akan tampil setelah dipublikasikan.'])
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
