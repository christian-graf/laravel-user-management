<?php

declare(strict_types=1);

namespace Fox\UserManagement\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use DMX\Support\Database\Eloquent\Models\Concerns\HasSnakeCaseAttributes;

/**
 * Class RolePermissions.
 *
 * @property int $id
 * @property int $permission_id
 * @property int $role_id
 *
 * @method static RolePermissions findOrFail(mixed $id, array $columns = ['*'])
 * @method static RolePermissions|null find(mixed $id, array $columns = ['*'])
 */
class RolePermissions extends Model
{
    use HasSnakeCaseAttributes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_id', 'role_id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'permission_id' => 'integer',
        'role_id' => 'integer',
    ];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        return config('permission.table_names.role_has_permissions', parent::getTable());
    }
}
