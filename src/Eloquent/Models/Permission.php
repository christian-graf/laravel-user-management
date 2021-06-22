<?php

declare(strict_types=1);

namespace Fox\UserManagement\Eloquent\Models;

use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use DMX\Support\Database\Eloquent\Models\Concerns\HasSnakeCaseAttributes;

/**
 * Class Permission.
 *
 * @property int    $id
 * @property string $name
 * @property string $guardName
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 *
 * @method static Permission findOrFail(mixed $id, array $columns = ['*'])
 * @method static Permission|null find(mixed $id, array $columns = ['*'])
 */
class Permission extends SpatiePermission implements PermissionContract
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
