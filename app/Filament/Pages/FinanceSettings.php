<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\ManagesSettings;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class FinanceSettings extends Page
{
    use ManagesSettings;

    protected string $view = 'filament.pages.settings-form';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Pengaturan Keuangan';

    protected static ?string $title = 'Pengaturan Keuangan';

    protected static ?int $navigationSort = 104;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function settingsGroup(): string
    {
        return 'finance';
    }

    public function settingFields(): array
    {
        return [
            'default_cash_account_id' => ['label' => 'ID Akun Kas Default', 'type' => 'number'],
            'payment_prefix' => ['label' => 'Prefix Nomor Pembayaran', 'type' => 'text', 'default' => 'PAY'],
            'bill_prefix' => ['label' => 'Prefix Nomor Tagihan', 'type' => 'text', 'default' => 'BILL'],
            'require_finance_approval' => ['label' => 'Wajib Approval Keuangan', 'type' => 'boolean', 'default' => true],
            'allow_backdated_transactions' => ['label' => 'Izinkan Transaksi Mundur Tanggal', 'type' => 'boolean', 'default' => false],
            'late_fee_notes' => ['label' => 'Catatan Denda/Tunggakan', 'type' => 'textarea'],
        ];
    }
}
