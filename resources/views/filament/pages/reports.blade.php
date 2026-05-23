<x-filament-panels::page>
    @php
        $activeReport = $this->activeReportMeta();
        $columns = $this->reportColumns();
        $rows = $this->reportRows();
        $tones = [
            'emerald' => 'border-emerald-100 bg-emerald-50 text-emerald-900',
            'gold' => 'border-yellow-100 bg-yellow-50 text-yellow-900',
            'gray' => 'border-gray-200 bg-gray-50 text-gray-800',
        ];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-lg border border-emerald-100 bg-white shadow-sm">
            <div class="border-b border-emerald-100 bg-gradient-to-r from-emerald-700 via-emerald-600 to-emerald-700 p-5 text-white sm:p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-sm font-semibold uppercase tracking-wide text-yellow-100">Pusat Laporan TPQ</p>
                        <h2 class="mt-2 text-2xl font-bold tracking-normal sm:text-3xl">{{ $activeReport['title'] }}</h2>
                        <p class="mt-2 text-sm leading-6 text-emerald-50">{{ $activeReport['description'] }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                    @can('reports.export')
                        <button
                            type="button"
                            wire:click="exportCsv"
                            class="inline-flex items-center justify-center rounded-md border border-white/40 bg-white px-4 py-2 text-sm font-semibold text-emerald-800 shadow-sm transition hover:bg-yellow-50"
                        >
                            Export CSV
                        </button>
                    @endcan
                </div>
            </div>
            </div>

            <div class="border-b border-gray-100 bg-white px-4 py-3 sm:px-6">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="text-sm font-semibold text-gray-800">
                        Jenis Laporan
                    </div>

                    <div class="-mx-1 flex gap-2 overflow-x-auto px-1 pb-1">
                        @foreach ($this->reports() as $key => $report)
                            <button
                                type="button"
                                wire:click="setReport('{{ $key }}')"
                                @class([
                                    'shrink-0 rounded-md border px-3 py-2 text-sm font-semibold transition',
                                    'border-emerald-700 bg-emerald-700 text-white shadow-sm' => $activeReport['label'] === $report['label'],
                                    'border-gray-200 bg-white text-gray-700 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-800' => $activeReport['label'] !== $report['label'],
                                ])
                            >
                                {{ $report['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-4 sm:p-6">
                <div class="grid gap-4 lg:grid-cols-12">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 lg:col-span-5">
                        <div class="mb-3 text-sm font-semibold text-gray-900">Periode</div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="grid gap-1 text-sm">
                                <span class="font-medium text-gray-700">Tanggal mulai</span>
                                <input type="date" wire:model.live="dateFrom" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                            </label>

                            <label class="grid gap-1 text-sm">
                                <span class="font-medium text-gray-700">Tanggal akhir</span>
                                <input type="date" wire:model.live="dateTo" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                            </label>
                        </div>

                        <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-gray-500">
                                Periode {{ $dateFrom ? \Illuminate\Support\Carbon::parse($dateFrom)->format('d M Y') : 'awal data' }}
                                sampai {{ $dateTo ? \Illuminate\Support\Carbon::parse($dateTo)->format('d M Y') : 'akhir data' }}.
                            </p>

                            <button type="button" wire:click="resetFilters" class="inline-flex items-center justify-center rounded-md border border-emerald-200 bg-white px-3 py-2 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-50">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-4 lg:col-span-7">
                        <div class="mb-3 text-sm font-semibold text-gray-900">Atribut</div>
                        <div class="grid gap-4 md:grid-cols-3">
                            <label class="grid gap-1 text-sm">
                                <span class="font-medium text-gray-700">Tahun ajaran</span>
                                <select wire:model.live="academicYearId" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                    <option value="">Semua</option>
                                    @foreach ($this->academicYearOptions() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="grid gap-1 text-sm">
                                <span class="font-medium text-gray-700">Program</span>
                                <select wire:model.live="programId" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                    <option value="">Semua</option>
                                    @foreach ($this->programOptions() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="grid gap-1 text-sm">
                                <span class="font-medium text-gray-700">Kelas</span>
                                <select wire:model.live="programClassId" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                    <option value="">Semua</option>
                                    @foreach ($this->classOptions() as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="grid gap-1 text-sm md:col-span-3">
                                <span class="font-medium text-gray-700">Status</span>
                                <select wire:model.live="status" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                                    <option value="">Semua</option>
                                    @foreach ($this->statusOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-semibold text-gray-950">Ringkasan</h3>
                    <span class="rounded-md bg-yellow-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-yellow-800">
                        {{ $activeReport['label'] }}
                    </span>
                </div>

                <div class="text-sm text-gray-500">
                    {{ $activeReport['title'] }}
                    <span class="hidden">.</span>
                    <span class="block sm:hidden">&nbsp;</span>
                    <span class="font-medium text-gray-700">
                        Periode: {{ $dateFrom ? \Illuminate\Support\Carbon::parse($dateFrom)->format('d M Y') : 'awal data' }} s/d {{ $dateTo ? \Illuminate\Support\Carbon::parse($dateTo)->format('d M Y') : 'akhir data' }}
                    </span>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($this->summaryCards() as $card)
                    <div class="rounded-lg border p-4 shadow-sm {{ $tones[$card['tone']] ?? $tones['gray'] }}">
                        <p class="text-sm font-medium opacity-80">{{ $card['label'] }}</p>
                        <p class="mt-2 break-words text-2xl font-bold tracking-normal">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-2 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div>
                    <h3 class="text-base font-semibold text-gray-950">{{ $activeReport['title'] }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ number_format($rows->count(), 0, ',', '.') }} baris data ditampilkan.</p>
                </div>

                <span class="w-fit rounded-md bg-yellow-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-yellow-800">
                    {{ $activeReport['label'] }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <div class="max-h-[520px] overflow-y-auto">
                    <table class="min-w-full border border-gray-200 text-sm border-separate border-spacing-0">
                        <thead class="sticky top-0 z-10 bg-gray-50 backdrop-blur border-b border-gray-300">

                            <tr>
                                @foreach ($columns as $index => $column)
                                    <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        {{ $column }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white text-gray-700">
                            @forelse ($rows as $row)
                                @php
                                    $values = collect($row)
                                        ->except(['status_raw', 'total_raw', 'records_raw', 'average_raw', 'amount_raw', 'remaining_raw', 'type_raw'])
                                        ->values()
                                        ->all();
                                @endphp

                                <tr class="transition hover:bg-emerald-50/60 odd:bg-white even:bg-gray-50/30">
                                    @foreach ($values as $i => $value)
                                        <td class="whitespace-nowrap px-4 py-3 {{ is_numeric($value) ? 'text-right' : '' }} border-gray-100">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ max(count($columns), 1) }}" class="px-4 py-14 text-center text-sm text-gray-500">
                                        Belum ada data untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-filament-panels::page>

