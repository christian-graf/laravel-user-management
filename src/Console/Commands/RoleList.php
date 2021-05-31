<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Database\Query\Builder as QueryBuilder;

class RoleList extends AbstractUserManagingCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'permission:role-list
        {--orderBy=id : Name of the field to order the list}
        {--orderDirection=ASC : Direction to order the list (ASC or DESC)}
    ';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Get a list of all available roles.';

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
            'guard_name',
        ];

        /** @var QueryBuilder $query */
        $query = $this->roleModelClass()::query();

        $orderBy = strtolower($this->option('orderBy'));
        $orderByDirection = strtoupper($this->option('orderDirection'));

        if (!in_array($orderBy, ['id', 'name', 'guard_name'])) {
            $this->error('Unknown order by value! You can only order by id, name or guard_name.');

            return -10;
        }

        if (!in_array($orderByDirection, ['ASC', 'DESC'])) {
            $orderByDirection = 'ASC';
        }

        $roleList =
            $query
                ->orderBy($orderBy, $orderByDirection)
                ->get([
                    'id',
                    'name',
                    'guard_name',
                ])
                ->toArray()
        ;

        $this->table($headers, $roleList);

        return 0;
    }
}
