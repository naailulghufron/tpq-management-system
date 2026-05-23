@props(['headers' => []])

<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-50">
        <tr>
            @foreach ($headers as $header)
                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 bg-white text-gray-700 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
        {{ $slot }}
    </tbody>
</table>
