<?php

namespace App\Filament\Widgets;

use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\StudentSavingsTransaction;
use App\Models\Teacher;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OverviewStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Lembaga';

    protected ?string $description = 'Metrik utama TPQ hari ini.';

    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = [
        'default' => 1,
        'sm' => 2,
        'xl' => 4,
    ];

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total santri aktif', $this->number($this->count(Student::class, ['status' => 'active'])))
                ->description('Santri terdaftar aktif')
                ->descriptionColor('success')
                ->color('success')
                ->icon(Heroicon::OutlinedUserGroup),

            Stat::make('Total guru', $this->number($this->count(Teacher::class, ['status' => 'active'])))
                ->description('Pengajar aktif')
                ->descriptionColor('success')
                ->color('success')
                ->icon(Heroicon::OutlinedAcademicCap),

            Stat::make('Total program aktif', $this->number($this->count(Program::class, ['status' => 'active'])))
                ->description('Program pendidikan berjalan')
                ->descriptionColor('warning')
                ->color('warning')
                ->icon(Heroicon::OutlinedBookOpen),

            Stat::make('Absensi hari ini', $this->attendanceToday())
                ->description('Hadir / total presensi santri')
                ->descriptionColor('success')
                ->color('success')
                ->icon(Heroicon::OutlinedClipboardDocumentCheck),

            Stat::make('Saldo kas', $this->rupiah($this->cashBalance()))
                ->description('Kas awal + transaksi posted')
                ->descriptionColor('warning')
                ->color('warning')
                ->icon(Heroicon::OutlinedBanknotes),

            Stat::make('Saldo tabungan santri', $this->rupiah($this->savingsBalance()))
                ->description('Dihitung dari transaksi posted')
                ->descriptionColor('success')
                ->color('success')
                ->icon(Heroicon::OutlinedWallet),

            Stat::make('Pembayaran bulan ini', $this->rupiah($this->paymentsThisMonth()))
                ->description(now()->translatedFormat('F Y'))
                ->descriptionColor('success')
                ->color('success')
                ->icon(Heroicon::OutlinedDocumentCurrencyDollar),

            Stat::make('Tunggakan', $this->rupiah($this->arrears()))
                ->description('Tagihan belum lunas')
                ->descriptionColor('danger')
                ->color('danger')
                ->icon(Heroicon::OutlinedBellAlert),
        ];
    }

    private function count(string $model, array $where = []): int
    {
        if (! Schema::hasTable((new $model)->getTable())) {
            return 0;
        }

        return $model::query()->where($where)->count();
    }

    private function attendanceToday(): string
    {
        if (! Schema::hasTable('student_attendances')) {
            return '0 / 0';
        }

        $query = StudentAttendance::query()
            ->whereHas('attendanceSession', fn ($query) => $query->whereDate('session_date', today()));

        $present = (clone $query)->where('attendance_status', 'present')->count();
        $total = $query->count();

        return "{$present} / {$total}";
    }

    private function cashBalance(): float
    {
        if (! Schema::hasTable('cash_accounts')) {
            return 0;
        }

        $opening = (float) CashAccount::query()->sum('opening_balance');

        if (! Schema::hasTable('cash_transactions')) {
            return $opening;
        }

        $transactions = (float) CashTransaction::query()
            ->where('status', 'posted')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'expense' THEN amount * -1 ELSE amount END), 0) as balance")
            ->value('balance');

        return $opening + $transactions;
    }

    private function savingsBalance(): float
    {
        if (! Schema::hasTable('student_savings_transactions')) {
            return 0;
        }

        return (float) StudentSavingsTransaction::query()
            ->where('status', 'posted')
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'withdrawal' THEN amount * -1 ELSE amount END), 0) as balance")
            ->value('balance');
    }

    private function paymentsThisMonth(): float
    {
        if (! Schema::hasTable('student_payments')) {
            return 0;
        }

        return (float) StudentPayment::query()
            ->where('status', 'posted')
            ->whereBetween('paid_at', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('amount');
    }

    private function arrears(): float
    {
        if (! Schema::hasTable('student_bills')) {
            return 0;
        }

        return (float) StudentBill::query()
            ->whereRaw('amount > paid_amount')
            ->whereNotIn('status', ['cancelled', 'void'])
            ->sum(DB::raw('amount - paid_amount'));
    }

    private function rupiah(float|int $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }

    private function number(float|int $number): string
    {
        return number_format((float) $number, 0, ',', '.');
    }
}
