<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Database\Eloquent\Model;

class SetPassword extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'user:set-password
        {id : Id of the designated user}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Set a user password.';

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

        $this->comment('Set password for user with ID ' . $userId . ' | ' . $user->email);
        $data = [
            'password' => (string) $this->secret('Password'),
            'password_confirmation' => (string) $this->secret('Confirm password'),
        ];

        if (!$this->validateUserInput($data, [self::RULE_PASSWORD, self::RULE_PASSWORD_CONFIRMATION])) {
            return -10;
        }

        if (function_exists('bcrypt')) {
            $user->setAttribute('password', bcrypt($data['password']));
        } else {
            $user->setAttribute('password', md5($data['password']));
        }

        if ($user->save() === true) {
            $this->info('Password successfully set.');
        } else {
            $this->error('Password could not be set!');

            return -1;
        }

        return 0;
    }
}
