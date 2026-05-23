<?php

namespace App\Providers;

use App\Policies\ActivityPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);

        Event::listen(Login::class, function (Login $event): void {
            $event->user->forceFill([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ])->saveQuietly();
        });
    }
}
