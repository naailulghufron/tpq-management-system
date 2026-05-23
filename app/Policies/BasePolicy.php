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

        return $user->hasRole('Super Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can($this->ability('view_any'));
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
        return $user->can($this->ability('restore'));
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
        return "{$this->permissionPrefix}.{$action}";
    }
}
