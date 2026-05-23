<x-public-layout title="Blog">
    <section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold text-emerald-700">Blog</p>
        <h1 class="mt-2 text-3xl font-semibold text-gray-950">Artikel dan kabar TPQ.</h1>
        <div class="mt-6 grid gap-4">
            @forelse ($posts as $post)
                <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-medium text-gray-500">{{ optional($post->published_at)->format('d M Y') }}</p>
                    <h2 class="mt-1 text-lg font-semibold text-gray-950">{{ $post->title }}</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">{{ $post->excerpt ?: str($post->content)->stripTags()->limit(180) }}</p>
                </article>
            @empty
                @include('public.partials.empty-state', ['title' => 'Belum ada artikel', 'message' => 'Artikel TPQ akan tampil setelah dipublikasikan.'])
            @endforelse
        </div>
    </section>
</x-public-layout>
