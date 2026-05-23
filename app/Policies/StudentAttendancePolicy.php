<?php

namespace App\Policies;

class StudentAttendancePolicy extends BasePolicy
{
    protected string $permissionPrefix = 'student_attendances';
}
