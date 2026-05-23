<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StudentPolicy extends BasePolicy
{
    protected string $permissionPrefix = 'students';

    public function view(User $user, Model $model): bool
    {
        if (! $user->can('students.view')) {
            return false;
        }

        if ($user->canAny(['students.create', 'students.update', 'students.delete', 'attendance.create', 'programs.view', 'finance.view'])) {
            return true;
        }

        if (! $model instanceof Student) {
            return false;
        }

        return $model->parents()->where('user_id', $user->id)->exists();
    }
}
