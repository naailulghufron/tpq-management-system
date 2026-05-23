<x-public-layout title="Program Pendidikan">
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold text-emerald-700">Program Pendidikan</p>
        <h1 class="mt-2 text-3xl font-semibold text-gray-950">Pilihan pembelajaran yang fleksibel dan bertahap.</h1>
        <div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($programs as $program)
                <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600">{{ $program->category?->name ?? 'Program TPQ' }}</p>
                    <h2 class="mt-2 text-lg font-semibold text-gray-950">{{ $program->name }}</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">{{ $program->description ?: 'Materi disusun bertahap sesuai kemampuan santri.' }}</p>
                </article>
            @empty
                @include('public.partials.empty-state', ['title' => 'Program belum tersedia', 'message' => 'Program pendidikan akan tampil setelah admin mengaktifkan data program.'])
            @endforelse
        </div>
    </section>
</x-public-layout>
