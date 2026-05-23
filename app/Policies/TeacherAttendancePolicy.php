<?php

namespace App\Policies;

class TeacherAttendancePolicy extends BasePolicy
{
    protected string $permissionPrefix = 'teacher_attendances';
}
