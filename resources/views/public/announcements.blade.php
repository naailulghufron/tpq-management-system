<x-public-layout title="Pengumuman">
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold text-emerald-700">Pengumuman</p>
        <h1 class="mt-2 text-3xl font-semibold text-gray-950">Informasi terbaru untuk santri dan wali.</h1>
        <div class="mt-6 grid gap-4">
            @forelse ($announcements as $announcement)
                <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($announcement->is_pinned)
                            <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">Penting</span>
                        @endif
                        <span class="text-xs text-gray-500">{{ optional($announcement->starts_at)->format('d M Y') }}</span>
                    </div>
                    <h2 class="mt-2 text-lg font-semibold text-gray-950">{{ $announcement->title }}</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">{{ str($announcement->content)->stripTags() }}</p>
                </article>
            @empty
                @include('public.partials.empty-state', ['title' => 'Belum ada pengumuman', 'message' => 'Pengumuman aktif akan tampil di halaman ini.'])
            @endforelse
        </div>
    </section>
</x-public-layout>
