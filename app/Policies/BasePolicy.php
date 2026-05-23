<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class BasePolicy
{
    protected string $permissionPrefix;

    public function before(User $user, string $ability): ?bool
    {
        if ($ability === 'forceDelete') {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can($this->ability('view'));
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can($this->ability('view'));
    }

    public function create(User $user): bool
    {
        return $user->can($this->ability('create'));
    }

    public function update(User $user, Model $model): bool
    {
        return $user->can($this->ability('update'));
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->can($this->ability('delete'));
    }

    public function restore(User $user, Model $model): bool
    {
        return $user->can($this->ability('update'));
    }

    public function forceDelete(User $user, Model $model): bool
    {
        return false;
    }

    public function approve(User $user, Model $model): bool
    {
        return $user->can($this->ability('approve'));
    }

    public function cancel(User $user, Model $model): bool
    {
        return $user->can($this->ability('cancel'));
    }

    protected function ability(string $action): string
    {
        $permission = $this->modulePermission();

        if ($permission === 'settings') {
            return 'settings.manage';
        }

        return "{$permission}.{$this->permissionAction($action)}";
    }

    private function modulePermission(): string
    {
        return match ($this->permissionPrefix) {
            'students', 'student_parents' => 'students',
            'teachers' => 'teachers',
            'program_categories', 'programs', 'program_levels', 'program_classes', 'program_materials',
            'student_programs', 'student_progress', 'student_scores' => 'programs',
            'attendance_sessions', 'student_attendances', 'teacher_attendances' => 'attendance',
            'cash_accounts', 'payment_categories', 'student_bills', 'student_payments', 'cash_transactions',
            'donations', 'expenses' => 'finance',
            'student_savings_accounts', 'student_savings_transactions' => 'savings',
            'post_categories', 'posts', 'announcements', 'galleries', 'pages' => 'blog',
            'academic_years', 'branches', 'settings' => 'settings',
            default => $this->permissionPrefix,
        };
    }

    private function permissionAction(string $action): string
    {
        return match ($action) {
            'viewAny', 'view_any', 'view' => 'view',
            'restore' => 'update',
            default => $action,
        };
    }
}
