<?php

declare(strict_types=1);

namespace Fox\UserManagement;

use Fox\UserManagement\Console\Commands\EditUser;
use Fox\UserManagement\Console\Commands\RoleList;
use Fox\UserManagement\Console\Commands\UserList;
use Fox\UserManagement\Console\Commands\UserRoles;
use Fox\UserManagement\Console\Commands\CreateUser;
use Fox\UserManagement\Console\Commands\SetPassword;
use Fox\UserManagement\Console\Commands\ActivateUser;
use Fox\UserManagement\Console\Commands\DeactivateUser;
use Fox\UserManagement\Eloquent\Models\RolePermissions;
use Fox\UserManagement\Eloquent\Observers\RolePermissionsObserver;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // load migrations
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            // register commands
            $this->commands([
                CreateUser::class,
                UserList::class,
                EditUser::class,
                ActivateUser::class,
                DeactivateUser::class,
                SetPassword::class,
                RoleList::class,
                UserRoles::class,
            ]);
        }

        // Define publishes...
        $this->publishes([
            __DIR__ . '/../config/permission.php' => $this->app->configPath('permission.php'),
        ], 'user-mgmt-config');

        // register Observers
        RolePermissions::observe(RolePermissionsObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge default configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/permission.php', 'permission');
    }

    /**
     * {@inheritdoc}
     */
    public function provides(): array
    {
        return [];
    }
}
