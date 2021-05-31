<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Database\Eloquent\Model;

class EditUser extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'user:edit
        {id : Id of the designated user}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Update common user information like email or name.';

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

        $this->comment('Edit user with ID ' . $userId . ' | ' . $user->email);
        $data = [
            'name' => (string) $this->ask('Name', $user->name),
            'email' => (string) $this->ask('Email', $user->email),
        ];

        if (!$this->validateUserInput($data, [self::RULE_NAME, self::RULE_EMAIL], $user->getKey())) {
            return -10;
        }

        $user->setAttribute('name', $data['name']);
        $user->setAttribute('email', $data['email']);

        if ($user->save() === true) {
            $this->info('User successfully updated.');
        } else {
            $this->error('User could not be saved!');

            return -1;
        }

        return 0;
    }
}
