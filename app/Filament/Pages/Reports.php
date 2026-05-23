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
use Spatie\Activitylog\Models\Activity;
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
