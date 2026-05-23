<?php

namespace App\Policies;

class SettingPolicy extends BasePolicy
{
    protected string $permissionPrefix = 'settings';

    protected function ability(string $action): string
    {
        return 'settings.manage';
    }
}
