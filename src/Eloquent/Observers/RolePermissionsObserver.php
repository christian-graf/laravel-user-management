<?php

declare(strict_types=1);

namespace Fox\UserManagement\Eloquent\Observers;

use Fox\UserManagement\Eloquent\Models\RolePermissions;

class RolePermissionsObserver
{
    /**
     * Handle the role permissions "created" event.
     *
     * @param RolePermissions $rolePermissions
     *
     * @return void
     */
    public function created(RolePermissions $rolePermissions)
    {
        // Clear Cache to make change valid instantly after a permission has been assigned to a role
        try {
            if (function_exists('cache')) {
                cache()->clear();
            }
        } catch (\Exception $exception) {
            error_log(__FILE__ . ' => ' . $exception->getMessage());
        }
    }

    /**
     * Handle the role permissions "updated" event.
     *
     * @param RolePermissions $rolePermissions
     *
     * @return void
     */
    public function updated(RolePermissions $rolePermissions)
    {
    }

    /**
     * Handle the role permissions "deleted" event.
     *
     * @param RolePermissions $rolePermissions
     *
     * @return void
     */
    public function deleted(RolePermissions $rolePermissions)
    {
        // Clear Cache to make change valid instantly after a permission has been assigned to a role
        try {
            if (function_exists('cache')) {
                cache()->clear();
            }
        } catch (\Exception $exception) {
            error_log(__FILE__ . ' => ' . $exception->getMessage());
        }
    }

    /**
     * Handle the role permissions "restored" event.
     *
     * @param RolePermissions $rolePermissions
     *
     * @return void
     */
    public function restored(RolePermissions $rolePermissions)
    {
    }

    /**
     * Handle the role permissions "force deleted" event.
     *
     * @param RolePermissions $rolePermissions
     *
     * @return void
     */
    public function forceDeleted(RolePermissions $rolePermissions)
    {
    }
}
