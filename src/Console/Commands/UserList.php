<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UserList extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'user:list
        {--active : Show only active users}
        {--inactive : Show only inactive/disabled users}
        {--orderBy=id : Name of the field to order the list}
        {--orderDirection=ASC : Direction to order the list (ASC or DESC)}
        {--showTimestamps : Add created and / updated datetime information to the list}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Get a list of all registered users.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $headers = [
            'id',
            'name',
            'email',
            'state',
            'roles',
        ];
        if ($this->option('showTimestamps') === true) {
            $headers[] = 'created at';
            $headers[] = 'last modified at';
        }

        /** @var QueryBuilder $query */
        $query = $this->userModelClass()::query();

        if ($this->option('active') === true) {
            $query->where('active', true);
        }

        if ($this->option('inactive') === true) {
            $query->where('active', false);
        }

        $orderBy = strtolower($this->option('orderBy'));
        $orderByDirection = strtoupper($this->option('orderDirection'));

        if (!in_array($orderBy, ['id', 'name', 'email'])) {
            $this->error('Unknown order by value! You can only order by id, name or email.');

            return -10;
        }

        if (!in_array($orderByDirection, ['ASC', 'DESC'])) {
            $orderByDirection = 'ASC';
        }

        $userList = [];
        foreach ($query->orderBy($orderBy, $orderByDirection)->get() as $key => $user) {
            /* @var Model $user */
            $userList[$key] = [
                $user->getKey(),
                $user->getAttributeValue('name'),
                $user->getAttributeValue('email'),
                $user->getAttributeValue('active') ? 'active' : 'inactive',
                implode(', ', $user->roleNames()),
            ];

            if ($this->option('showTimestamps') === true) {
                $createdAt = $user->getAttributeValue('createdAt');
                $updatedAt = $user->getAttributeValue('updatedAt');

                $userList[$key][] = $createdAt ? $createdAt->toIso8601String() : '?';
                $userList[$key][] = $updatedAt ? $updatedAt->toIso8601String() : '';
            }
        }
        $this->table($headers, $userList);

        return 0;
    }
}
