<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'user:create
        {email : Email of the new user}
        {name : The name of the new user}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create a new application user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = [
            'email' => (string) $this->argument('email'),
            'name' => (string) $this->argument('name'),
        ];

        if (!$this->validateUserInput($data, [self::RULE_NAME, self::RULE_EMAIL])) {
            return -10;
        }

        $modelClass = $this->userModelClass();

        $initialPassword = Str::random(16);
        /** @var Model $user */
        $user = new $modelClass($data);
        $user->setAttribute('active', true);
        if (function_exists('bcrypt')) {
            $user->setAttribute('password', bcrypt($initialPassword));
        } else {
            $user->setAttribute('password', md5($initialPassword));
        }

        if ($user->save() === true) {
            $this->info('User successfully created.');
            $this->line('Id:' . $user->getAttributeValue('id'));
            $this->line('Name:' . $user->getAttributeValue('name'));
            $this->line('Email:' . $user->getAttributeValue('email'));
            $this->line('Initial password:' . $initialPassword);
        } else {
            $this->error('User could not be created!');

            return -20;
        }

        return 0;
    }
}
