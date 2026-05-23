<?php

namespace App\Policies;

class CashTransactionPolicy extends BasePolicy
{
    protected string $permissionPrefix = 'cash_transactions';
}
