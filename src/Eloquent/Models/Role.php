<?php

declare(strict_types=1);

namespace Fox\UserManagement\Eloquent\Models;

use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Contracts\Role as RoleContract;
use DMX\Support\Database\Eloquent\Models\Concerns\HasSnakeCaseAttributes;

/**
 * Class Role.
 *
 * @property int    $id
 * @property string $name
 * @property string $guardName
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @method static Role findOrFail(mixed $id, array $columns = ['*'])
 * @method static Role|null find(mixed $id, array $columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Collection|Role[] all(array $columns = ['*'])
 */
class Role extends SpatieRole implements RoleContract
{
    use HasSnakeCaseAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'guard_name',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'name' => 'string',
        'guard_name' => 'string',
    ];

    /**
     * {@inheritdoc}
     */
    public $timestamps = true;
}
