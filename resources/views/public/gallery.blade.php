<x-public-layout title="Galeri">
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold text-emerald-700">Galeri</p>
        <h1 class="mt-2 text-3xl font-semibold text-gray-950">Dokumentasi kegiatan santri.</h1>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleries as $gallery)
                <article class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="aspect-[4/3] bg-emerald-50">
                        <img src="{{ asset($gallery->image_path) }}" alt="{{ $gallery->title }}" class="h-full w-full object-cover" loading="lazy">
                    </div>
                    <div class="p-4">
                        <h2 class="font-semibold text-gray-950">{{ $gallery->title }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ $gallery->caption }}</p>
                    </div>
                </article>
            @empty
                @include('public.partials.empty-state', ['title' => 'Galeri belum tersedia', 'message' => 'Foto kegiatan akan tampil setelah dipublikasikan.'])
            @endforelse
        </div>
    </section>
</x-public-layout>
