<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class DeactivateUser extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'user:deactivate
        {id* : Id(s) of the designated user(s)}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Deactivate one or more users.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userIdList = [];
        foreach ($this->argument('id') as $userId) {
            if (!is_numeric($userId)) {
                $this->error('One or more given user id(s) are invalid!');

                return -10;
            }

            $userIdList[] = (int) $userId;
        }

        /** @var Collection|Model[] $userList */
        $userList = $this->userModelClass()::query()->findOrFail($userIdList);
        foreach ($userList as $user) {
            $user->setAttribute('active', true);

            if ($user->save() === true) {
                $this->info('User #' . $user->getKey() . ' successfully deactivated.');
            } else {
                $this->error('User #' . $user->getKey() . ' could not be deactivated.');
            }
        }

        return 0;
    }
}
