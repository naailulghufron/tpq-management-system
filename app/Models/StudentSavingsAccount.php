<?php

namespace App\Models;

use App\Models\Concerns\HasAuditRelations;
use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSavingsAccount extends Model
{
    use HasAuditRelations, HasFactory, LogsModelActivity, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['balance'];

    protected function casts(): array
    {
        return [
            'opened_at' => 'date',
            'closed_at' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    protected function balance(): Attribute
    {
        return Attribute::get(function (): string {
            $deposits = $this->transactions()->posted()->where('type', 'deposit')->sum('amount');
            $withdrawals = $this->transactions()->posted()->where('type', 'withdrawal')->sum('amount');
            $adjustments = $this->transactions()->posted()->where('type', 'adjustment')->sum('amount');

            return number_format((float) $deposits - (float) $withdrawals + (float) $adjustments, 2, '.', '');
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StudentSavingsTransaction::class);
    }
}
