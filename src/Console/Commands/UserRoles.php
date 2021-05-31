<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'user:roles
        {id : Id of the designated user}
        {--assign= : A list of roles to assign to the designated user, separated by ,}
        {--remove= : A list of roles to remove from the designated user, separated by ,}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'List, assign or remove roles from a user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = (int) $this->argument('id');

        /** @var Model $user */
        $user = $this->userModelClass()::query()->findOrFail($userId);

        $assign = $this->option('assign');
        if (!empty($assign)) {
            $assign = explode(',', $assign);
        }
        $remove = $this->option('remove');
        if (!empty($remove)) {
            $remove = explode(',', $remove);
        }

        $this->info('User #' . $userId . ' | ' . $user->email);

        try {
            if (empty($assign) && empty($remove)) {
                $this->comment('Assigned roles: ' . implode(', ', $user->roleNames()));
            } else {
                if (!empty($assign)) {
                    foreach ($assign as $role) {
                        $user->assignRole($role);
                        $this->line('Role "' . $role . '" assigned');
                    }
                }
                if (!empty($remove)) {
                    foreach ($remove as $role) {
                        $user->removeRole($role);
                        $this->line('Role "' . $role . '" removed');
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            return -1;
        }

        return 0;
    }
}
