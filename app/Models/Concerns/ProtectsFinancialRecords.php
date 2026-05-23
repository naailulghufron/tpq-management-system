<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use LogicException;

trait ProtectsFinancialRecords
{
    protected static function bootProtectsFinancialRecords(): void
    {
        static::deleting(function ($model): void {
            if ($model->isForceDeleting()) {
                throw new LogicException('Financial and savings transactions cannot be hard deleted.');
            }
        });
    }

    public function scopePosted(Builder $query): Builder
    {
        return $query->where('status', 'posted');
    }

    public function cancelWithReversal(?User $user = null, ?string $reason = null): static
    {
        if ($this->status === 'cancelled') {
            throw new LogicException('This transaction has already been cancelled.');
        }

        if ($this->reversal_of_id !== null) {
            throw new LogicException('A reversal transaction cannot be cancelled again.');
        }

        return DB::transaction(function () use ($user, $reason) {
            $reversal = $this->replicate([
                'status',
                'posted_at',
                'approved_by',
                'approved_at',
                'cancelled_by',
                'cancelled_at',
                'cancellation_reason',
                'reversal_of_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

            $numberColumn = $this->transactionNumberColumn();

            $reversalAttributes = [
                $numberColumn => $this->makeReversalNumber($numberColumn),
                'amount' => $this->reversalAmount(),
                'status' => 'posted',
                'posted_at' => now(),
                'reversal_of_id' => $this->getKey(),
                'created_by' => $user?->getKey(),
                'updated_by' => $user?->getKey(),
                'approved_by' => $user?->getKey(),
                'approved_at' => now(),
            ];

            if (array_key_exists('notes', $this->getAttributes())) {
                $reversalAttributes['notes'] = trim(($this->notes ?? '').PHP_EOL.'Reversal: '.$reason);
            }

            if (array_key_exists('description', $this->getAttributes())) {
                $reversalAttributes['description'] = trim(($this->description ?? '').PHP_EOL.'Reversal: '.$reason);
            }

            $reversal->forceFill($reversalAttributes)->save();

            $this->forceFill([
                'status' => 'cancelled',
                'cancelled_by' => $user?->getKey(),
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
                'updated_by' => $user?->getKey(),
            ])->save();

            activity()
                ->performedOn($this)
                ->causedBy($user)
                ->event('cancelled')
                ->withProperties([
                    'reason' => $reason,
                    'reversal_id' => $reversal->getKey(),
                    'reversal_number' => $reversal->{$numberColumn},
                ])
                ->log('Transaction cancelled with reversal.');

            return $reversal;
        });
    }

    protected function transactionNumberColumn(): string
    {
        foreach (['transaction_number', 'payment_number', 'donation_number', 'expense_number'] as $column) {
            if (array_key_exists($column, $this->getAttributes())) {
                return $column;
            }
        }

        throw new LogicException('No transaction number column is defined for this model.');
    }

    protected function makeReversalNumber(string $numberColumn): string
    {
        return 'REV-'.$this->{$numberColumn}.'-'.now()->format('YmdHis');
    }

    protected function reversalAmount(): float
    {
        return (float) $this->amount * -1;
    }
}
