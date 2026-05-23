<?php

namespace App\Filament\Pages;

use App\Models\AcademicYear;
use App\Models\CashTransaction;
use App\Models\Program;
use App\Models\ProgramClass;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\StudentProgress;
use App\Models\StudentSavingsTransaction;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use UnitEnum;

class Reports extends Page
{
    protected string $view = 'filament.pages.reports';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $title = 'Laporan';

    protected static ?int $navigationSort = 90;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public ?int $academicYearId = null;

    public ?int $programId = null;

    public ?int $programClassId = null;

    public ?string $status = null;

    public string $activeReport = 'students';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo = now()->endOfMonth()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('reports.view') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function resetFilters(): void
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo = now()->endOfMonth()->toDateString();
        $this->academicYearId = null;
        $this->programId = null;
        $this->programClassId = null;
        $this->status = null;
    }

    public function setReport(string $report): void
    {
        if (! array_key_exists($report, $this->reports())) {
            return;
        }

        $this->activeReport = $report;
        $this->status = null;
    }

    public function reports(): array
    {
        return [
            'students' => [
                'label' => 'Santri',
                'title' => 'Laporan Santri',
                'description' => 'Data santri berdasarkan tanggal pendaftaran, status, tahun ajaran, program, dan kelas.',
            ],
            'attendance' => [
                'label' => 'Absensi',
                'title' => 'Laporan Absensi',
                'description' => 'Rekap kehadiran santri dalam rentang tanggal dan kelas yang dipilih.',
            ],
            'education' => [
                'label' => 'Pendidikan',
                'title' => 'Laporan Pendidikan per Program',
                'description' => 'Ringkasan progress pembelajaran santri per program pendidikan.',
            ],
            'payments' => [
                'label' => 'Pembayaran',
                'title' => 'Laporan Pembayaran',
                'description' => 'Rekap transaksi pembayaran santri berdasarkan status dan tanggal pembayaran.',
            ],
            'arrears' => [
                'label' => 'Tunggakan',
                'title' => 'Laporan Tunggakan',
                'description' => 'Daftar tagihan santri yang masih memiliki sisa pembayaran.',
            ],
            'cash' => [
                'label' => 'Kas',
                'title' => 'Laporan Kas',
                'description' => 'Ringkasan arus kas masuk dan keluar dalam periode terpilih.',
            ],
            'savings' => [
                'label' => 'Tabungan',
                'title' => 'Laporan Tabungan',
                'description' => 'Rekap transaksi tabungan santri berdasarkan jenis dan status transaksi.',
            ],
            'activities' => [
                'label' => 'Aktivitas',
                'title' => 'Laporan Aktivitas User',
                'description' => 'Audit aktivitas penting pengguna yang tercatat oleh sistem.',
            ],
        ];
    }

    public function activeReportMeta(): array
    {
        return $this->reports()[$this->activeReport] ?? $this->reports()['students'];
    }

    public function statusOptions(): array
    {
        return match ($this->activeReport) {
            'attendance' => [
                'present' => 'Hadir',
                'absent' => 'Tidak hadir',
                'sick' => 'Sakit',
                'permission' => 'Izin',
                'late' => 'Terlambat',
            ],
            'payments', 'cash', 'savings' => [
                'draft' => 'Draft',
                'posted' => 'Posted',
                'cancelled' => 'Dibatalkan',
            ],
            'activities' => [
                'created' => 'Dibuat',
                'updated' => 'Diubah',
                'deleted' => 'Dihapus',
                'cancelled' => 'Dibatalkan',
            ],
            default => [
                'active' => 'Aktif',
                'inactive' => 'Tidak aktif',
                'draft' => 'Draft',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'completed' => 'Selesai',
            ],
        };
    }

    public function academicYearOptions(): Collection
    {
        return $this->tableExists('academic_years')
            ? AcademicYear::query()->orderByDesc('start_date')->pluck('name', 'id')
            : collect();
    }

    public function programOptions(): Collection
    {
        return $this->tableExists('programs')
            ? Program::query()->orderBy('name')->pluck('name', 'id')
            : collect();
    }

    public function classOptions(): Collection
    {
        return $this->tableExists('program_classes')
            ? ProgramClass::query()->orderBy('name')->pluck('name', 'id')
            : collect();
    }

    public function summaryCards(): array
    {
        $rows = $this->reportRows();

        return match ($this->activeReport) {
            'students' => [
                ['label' => 'Total data', 'value' => $this->formatNumber($rows->count()), 'tone' => 'emerald'],
                ['label' => 'Santri aktif', 'value' => $this->formatNumber($rows->where('status_raw', 'active')->count()), 'tone' => 'gold'],
                ['label' => 'Cabang terisi', 'value' => $this->formatNumber($rows->pluck('branch')->filter(fn ($branch) => $branch !== '-')->unique()->count()), 'tone' => 'gray'],
            ],
            'attendance' => [
                ['label' => 'Total absensi', 'value' => $this->formatNumber($rows->sum('total_raw')), 'tone' => 'emerald'],
                ['label' => 'Hadir', 'value' => $this->formatNumber($rows->where('status_raw', 'present')->sum('total_raw')), 'tone' => 'gold'],
                ['label' => 'Tidak hadir', 'value' => $this->formatNumber($rows->where('status_raw', 'absent')->sum('total_raw')), 'tone' => 'gray'],
            ],
            'education' => [
                ['label' => 'Program', 'value' => $this->formatNumber($rows->count()), 'tone' => 'emerald'],
                ['label' => 'Data progress', 'value' => $this->formatNumber($rows->sum('records_raw')), 'tone' => 'gold'],
                ['label' => 'Rata-rata progress', 'value' => number_format((float) $rows->avg('average_raw'), 1, ',', '.').'%', 'tone' => 'gray'],
            ],
            'payments' => [
                ['label' => 'Transaksi', 'value' => $this->formatNumber($rows->sum('records_raw')), 'tone' => 'emerald'],
                ['label' => 'Total pembayaran', 'value' => $this->rupiah($rows->sum('amount_raw')), 'tone' => 'gold'],
                ['label' => 'Posted', 'value' => $this->rupiah($rows->where('status_raw', 'posted')->sum('amount_raw')), 'tone' => 'gray'],
            ],
            'arrears' => [
                ['label' => 'Tagihan', 'value' => $this->formatNumber($rows->count()), 'tone' => 'emerald'],
                ['label' => 'Total tunggakan', 'value' => $this->rupiah($rows->sum('remaining_raw')), 'tone' => 'gold'],
                ['label' => 'Tunggakan terbesar', 'value' => $this->rupiah($rows->max('remaining_raw')), 'tone' => 'gray'],
            ],
            'cash' => [
                ['label' => 'Transaksi', 'value' => $this->formatNumber($rows->sum('records_raw')), 'tone' => 'emerald'],
                ['label' => 'Kas masuk', 'value' => $this->rupiah($rows->where('type_raw', 'income')->sum('amount_raw')), 'tone' => 'gold'],
                ['label' => 'Kas keluar', 'value' => $this->rupiah($rows->where('type_raw', 'expense')->sum('amount_raw')), 'tone' => 'gray'],
            ],
            'savings' => [
                ['label' => 'Transaksi', 'value' => $this->formatNumber($rows->sum('records_raw')), 'tone' => 'emerald'],
                ['label' => 'Setoran', 'value' => $this->rupiah($rows->where('type_raw', 'deposit')->sum('amount_raw')), 'tone' => 'gold'],
                ['label' => 'Penarikan', 'value' => $this->rupiah($rows->where('type_raw', 'withdrawal')->sum('amount_raw')), 'tone' => 'gray'],
            ],
            'activities' => [
                ['label' => 'Aktivitas', 'value' => $this->formatNumber($rows->count()), 'tone' => 'emerald'],
                ['label' => 'User aktif', 'value' => $this->formatNumber($rows->pluck('user')->filter(fn ($user) => $user !== '-')->unique()->count()), 'tone' => 'gold'],
                ['label' => 'Modul', 'value' => $this->formatNumber($rows->pluck('module')->filter()->unique()->count()), 'tone' => 'gray'],
            ],
            default => [],
        };
    }

    public function reportColumns(): array
    {
        return match ($this->activeReport) {
            'students' => ['Nomor Santri', 'Nama', 'Cabang', 'Status'],
            'attendance' => ['Status', 'Total', 'Persentase'],
            'education' => ['Program', 'Data Progress', 'Rata-rata'],
            'payments' => ['Status', 'Transaksi', 'Nominal'],
            'arrears' => ['Tagihan', 'Santri', 'Sisa', 'Status'],
            'cash' => ['Jenis', 'Status', 'Transaksi', 'Nominal'],
            'savings' => ['Jenis', 'Status', 'Transaksi', 'Nominal'],
            'activities' => ['Aktivitas', 'Modul', 'User', 'Waktu'],
            default => [],
        };
    }

    public function reportRows(): Collection
    {
        return match ($this->activeReport) {
            'students' => $this->studentReport()->map(fn (Student $student) => [
                'student_number' => $student->student_number ?? '-',
                'name' => $student->name,
                'branch' => $student->branch?->name ?? '-',
                'status' => $this->titleCase($student->status),
                'status_raw' => $student->status,
            ]),
            'attendance' => $this->attendanceRows(),
            'education' => $this->educationReport()->map(fn ($row) => [
                'program' => $row->program_name,
                'records' => $this->formatNumber($row->total_records),
                'average' => number_format((float) $row->average_progress, 1, ',', '.').'%',
                'records_raw' => (int) $row->total_records,
                'average_raw' => (float) $row->average_progress,
            ]),
            'payments' => $this->paymentReport()->map(fn ($row) => [
                'status' => $this->titleCase($row->status),
                'records' => $this->formatNumber($row->total_records),
                'amount' => $this->rupiah($row->total_amount),
                'status_raw' => $row->status,
                'records_raw' => (int) $row->total_records,
                'amount_raw' => (float) $row->total_amount,
            ]),
            'arrears' => $this->arrearsReport()->map(fn (StudentBill $bill) => [
                'bill_number' => $bill->bill_number,
                'student' => $bill->student?->name ?? '-',
                'remaining' => $this->rupiah($bill->amount - $bill->paid_amount),
                'status' => $this->titleCase($bill->status),
                'remaining_raw' => (float) ($bill->amount - $bill->paid_amount),
            ]),
            'cash' => $this->transactionRows($this->cashReport()),
            'savings' => $this->transactionRows($this->savingsReport()),
            'activities' => $this->activityReport()->map(fn (Activity $activity) => [
                'description' => $activity->description,
                'module' => $activity->log_name,
                'user' => $activity->causer?->name ?? '-',
                'created_at' => optional($activity->created_at)->format('d M Y H:i'),
            ]),
            default => collect(),
        };
    }

    public function exportCsv(): StreamedResponse
    {
        abort_unless(auth()->user()?->can('reports.export'), 403);

        $filename = Str::slug($this->activeReportMeta()['title']).'-'.now()->format('Ymd-His').'.csv';
        $columns = $this->reportColumns();
        $rows = $this->reportRows();

        activity('reports')
            ->causedBy(auth()->user())
            ->withProperties([
                'report' => $this->activeReport,
                'date_from' => $this->dateFrom,
                'date_to' => $this->dateTo,
            ])
            ->event('exported')
            ->log('export laporan');

        return response()->streamDownload(function () use ($columns, $rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            foreach ($rows as $row) {
                $values = collect($row)
                    ->except(['status_raw', 'total_raw', 'records_raw', 'average_raw', 'amount_raw', 'remaining_raw', 'type_raw'])
                    ->values()
                    ->all();

                fputcsv($handle, $values);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function studentReport(): Collection
    {
        if (! $this->tableExists('students')) {
            return collect();
        }

        return Student::query()
            ->with('branch')
            ->when($this->status, fn (Builder $query) => $query->where('status', $this->status))
            ->when($this->dateFrom, fn (Builder $query) => $query->whereDate('enrolled_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn (Builder $query) => $query->whereDate('enrolled_at', '<=', $this->dateTo))
            ->latest()
            ->limit(20)
            ->get();
    }

    public function attendanceReport(): Collection
    {
        if (! $this->tableExists('student_attendances')) {
            return collect();
        }

        return StudentAttendance::query()
            ->select('attendance_status', DB::raw('COUNT(*) as total'))
            ->whereHas('attendanceSession', function (Builder $query): void {
                $this->applyDateRange($query, 'session_date');
                $query->when($this->programClassId, fn (Builder $query) => $query->where('program_class_id', $this->programClassId));
            })
            ->when($this->status, fn (Builder $query) => $query->where('attendance_status', $this->status))
            ->groupBy('attendance_status')
            ->orderBy('attendance_status')
            ->get();
    }

    public function educationReport(): Collection
    {
        if (! $this->tableExists('student_progress')) {
            return collect();
        }

        return StudentProgress::query()
            ->join('student_programs', 'student_programs.id', '=', 'student_progress.student_program_id')
            ->join('program_classes', 'program_classes.id', '=', 'student_programs.program_class_id')
            ->join('program_levels', 'program_levels.id', '=', 'program_classes.program_level_id')
            ->join('programs', 'programs.id', '=', 'program_levels.program_id')
            ->select('programs.name as program_name', DB::raw('COUNT(student_progress.id) as total_records'), DB::raw('AVG(student_progress.progress_percent) as average_progress'))
            ->when($this->programId, fn (Builder $query) => $query->where('programs.id', $this->programId))
            ->when($this->programClassId, fn (Builder $query) => $query->where('program_classes.id', $this->programClassId))
            ->when($this->status, fn (Builder $query) => $query->where('student_progress.status', $this->status))
            ->groupBy('programs.name')
            ->orderBy('programs.name')
            ->get();
    }

    public function paymentReport(): Collection
    {
        if (! $this->tableExists('student_payments')) {
            return collect();
        }

        return StudentPayment::query()
            ->select('status', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(amount) as total_amount'))
            ->when($this->status, fn (Builder $query) => $query->where('status', $this->status))
            ->tap(fn (Builder $query) => $this->applyDateRange($query, 'paid_at'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();
    }

    public function arrearsReport(): Collection
    {
        if (! $this->tableExists('student_bills')) {
            return collect();
        }

        return StudentBill::query()
            ->with('student')
            ->whereRaw('amount > paid_amount')
            ->when($this->academicYearId, fn (Builder $query) => $query->where('academic_year_id', $this->academicYearId))
            ->when($this->status, fn (Builder $query) => $query->where('status', $this->status))
            ->orderByDesc(DB::raw('amount - paid_amount'))
            ->limit(20)
            ->get();
    }

    public function cashReport(): Collection
    {
        if (! $this->tableExists('cash_transactions')) {
            return collect();
        }

        return CashTransaction::query()
            ->select('type', 'status', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(amount) as total_amount'))
            ->when($this->status, fn (Builder $query) => $query->where('status', $this->status))
            ->tap(fn (Builder $query) => $this->applyDateRange($query, 'transaction_date'))
            ->groupBy('type', 'status')
            ->orderBy('type')
            ->get();
    }

    public function savingsReport(): Collection
    {
        if (! $this->tableExists('student_savings_transactions')) {
            return collect();
        }

        return StudentSavingsTransaction::query()
            ->select('type', 'status', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(amount) as total_amount'))
            ->when($this->status, fn (Builder $query) => $query->where('status', $this->status))
            ->tap(fn (Builder $query) => $this->applyDateRange($query, 'transaction_date'))
            ->groupBy('type', 'status')
            ->orderBy('type')
            ->get();
    }

    public function activityReport(): Collection
    {
        if (! $this->tableExists('activity_log')) {
            return collect();
        }

        return Activity::query()
            ->with('causer')
            ->when($this->status, fn (Builder $query) => $query->where('event', $this->status))
            ->tap(fn (Builder $query) => $this->applyDateRange($query, 'created_at'))
            ->latest()
            ->limit(20)
            ->get();
    }

    public function rupiah(float|int|null $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    private function attendanceRows(): Collection
    {
        $report = $this->attendanceReport();
        $total = (int) $report->sum('total');

        return $report->map(fn ($row) => [
            'status' => $this->titleCase($row->attendance_status),
            'total' => $this->formatNumber($row->total),
            'percentage' => $total > 0 ? number_format(((int) $row->total / $total) * 100, 1, ',', '.').'%' : '0%',
            'status_raw' => $row->attendance_status,
            'total_raw' => (int) $row->total,
        ]);
    }

    private function transactionRows(Collection $report): Collection
    {
        return $report->map(fn ($row) => [
            'type' => $this->titleCase($row->type),
            'status' => $this->titleCase($row->status),
            'records' => $this->formatNumber($row->total_records),
            'amount' => $this->rupiah($row->total_amount),
            'type_raw' => $row->type,
            'status_raw' => $row->status,
            'records_raw' => (int) $row->total_records,
            'amount_raw' => (float) $row->total_amount,
        ]);
    }

    private function formatNumber(float|int|null $number): string
    {
        return number_format((float) $number, 0, ',', '.');
    }

    private function titleCase(?string $value): string
    {
        return Str::of($value ?? '-')->replace('_', ' ')->title()->toString();
    }

    private function applyDateRange(Builder $query, string $column): void
    {
        $query
            ->when($this->dateFrom, fn (Builder $query) => $query->whereDate($column, '>=', $this->dateFrom))
            ->when($this->dateTo, fn (Builder $query) => $query->whereDate($column, '<=', $this->dateTo));
    }

    private function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (Throwable) {
            return false;
        }
    }
}
