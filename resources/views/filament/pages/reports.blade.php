<x-filament-panels::page>
    <div class="space-y-6">
        <section class="rounded-lg border border-emerald-100 bg-white p-4 shadow-sm">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-6">
                <label class="grid gap-1 text-sm">
                    <span class="font-medium text-gray-700">Tanggal mulai</span>
                    <input type="date" wire:model.live="dateFrom" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                </label>

                <label class="grid gap-1 text-sm">
                    <span class="font-medium text-gray-700">Tanggal akhir</span>
                    <input type="date" wire:model.live="dateTo" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                </label>

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

                <label class="grid gap-1 text-sm">
                    <span class="font-medium text-gray-700">Status</span>
                    <select wire:model.live="status" class="rounded-md border-gray-300 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                        <option value="">Semua</option>
                        @foreach (['active', 'inactive', 'draft', 'posted', 'cancelled', 'present', 'absent', 'sick', 'permission', 'late', 'created', 'updated', 'deleted'] as $item)
                            <option value="{{ $item }}">{{ str($item)->replace('_', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" wire:click="resetFilters" class="rounded-md border border-emerald-200 px-3 py-2 text-sm font-semibold text-emerald-800 hover:bg-emerald-50">
                    Reset filter
                </button>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-report-card title="Laporan santri">
                <x-report-table :headers="['No. Santri', 'Nama', 'Cabang', 'Status']">
                    @forelse ($this->studentReport() as $student)
                        <tr>
                            <td>{{ $student->student_number }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->branch?->name ?? '-' }}</td>
                            <td>{{ $student->status }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="4" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan absensi">
                <x-report-table :headers="['Status', 'Total']">
                    @forelse ($this->attendanceReport() as $row)
                        <tr>
                            <td>{{ str($row->attendance_status)->title() }}</td>
                            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="2" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan pendidikan per program">
                <x-report-table :headers="['Program', 'Data progress', 'Rata-rata']">
                    @forelse ($this->educationReport() as $row)
                        <tr>
                            <td>{{ $row->program_name }}</td>
                            <td>{{ number_format($row->total_records, 0, ',', '.') }}</td>
                            <td>{{ number_format((float) $row->average_progress, 1, ',', '.') }}%</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="3" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan pembayaran">
                <x-report-table :headers="['Status', 'Transaksi', 'Nominal']">
                    @forelse ($this->paymentReport() as $row)
                        <tr>
                            <td>{{ str($row->status)->title() }}</td>
                            <td>{{ number_format($row->total_records, 0, ',', '.') }}</td>
                            <td>{{ $this->rupiah($row->total_amount) }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="3" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan tunggakan">
                <x-report-table :headers="['Tagihan', 'Santri', 'Sisa', 'Status']">
                    @forelse ($this->arrearsReport() as $bill)
                        <tr>
                            <td>{{ $bill->bill_number }}</td>
                            <td>{{ $bill->student?->name ?? '-' }}</td>
                            <td>{{ $this->rupiah($bill->amount - $bill->paid_amount) }}</td>
                            <td>{{ $bill->status }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="4" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan kas">
                <x-report-table :headers="['Jenis', 'Status', 'Transaksi', 'Nominal']">
                    @forelse ($this->cashReport() as $row)
                        <tr>
                            <td>{{ str($row->type)->title() }}</td>
                            <td>{{ str($row->status)->title() }}</td>
                            <td>{{ number_format($row->total_records, 0, ',', '.') }}</td>
                            <td>{{ $this->rupiah($row->total_amount) }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="4" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan tabungan">
                <x-report-table :headers="['Jenis', 'Status', 'Transaksi', 'Nominal']">
                    @forelse ($this->savingsReport() as $row)
                        <tr>
                            <td>{{ str($row->type)->title() }}</td>
                            <td>{{ str($row->status)->title() }}</td>
                            <td>{{ number_format($row->total_records, 0, ',', '.') }}</td>
                            <td>{{ $this->rupiah($row->total_amount) }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="4" />
                    @endforelse
                </x-report-table>
            </x-report-card>

            <x-report-card title="Laporan aktivitas user">
                <x-report-table :headers="['Aktivitas', 'Modul', 'User', 'Waktu']">
                    @forelse ($this->activityReport() as $activity)
                        <tr>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->log_name }}</td>
                            <td>{{ $activity->causer?->name ?? '-' }}</td>
                            <td>{{ optional($activity->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <x-report-empty colspan="4" />
                    @endforelse
                </x-report-table>
            </x-report-card>
        </div>
    </div>
</x-filament-panels::page>
