<?php

namespace App\Policies;

class StudentPaymentPolicy extends BasePolicy
{
    protected string $permissionPrefix = 'student_payments';
}
