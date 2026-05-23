@props(['title'])

<section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-100 bg-emerald-50 px-4 py-3">
        <h2 class="text-sm font-semibold text-emerald-950">{{ $title }}</h2>
    </div>
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</section>
