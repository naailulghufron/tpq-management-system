<?php

namespace App\Models;

use App\Models\Concerns\HasAuditRelations;
use App\Models\Concerns\LogsModelActivity;
use App\Models\Concerns\ProtectsFinancialRecords;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPayment extends Model
{
    use HasAuditRelations, HasFactory, LogsModelActivity, ProtectsFinancialRecords, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'paid_at' => 'date',
            'amount' => 'decimal:2',
            'posted_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function studentBill(): BelongsTo
    {
        return $this->belongsTo(StudentBill::class);
    }

    public function cashAccount(): BelongsTo
    {
        return $this->belongsTo(CashAccount::class);
    }
}
