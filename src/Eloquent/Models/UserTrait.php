<?php

declare(strict_types=1);

namespace Fox\UserManagement\Eloquent\Models;

use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;
use DMX\Support\Database\Eloquent\Models\Concerns\CanBeFilledByRequest;
use DMX\Support\Database\Eloquent\Models\Concerns\HasSnakeCaseAttributes;

/**
 * Class User.
 *
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property Carbon|null $emailVerifiedAt
 * @property string      $password
 * @property bool        $active
 * @property Carbon      $createdAt
 * @property Carbon|null $updatedAt
 *
 * @method static UserTrait findOrFail(mixed $id, array $columns = ['*'])
 * @method static UserTrait|null find(mixed $id, array $columns = ['*'])
 */
trait UserTrait
{
    use HasRoles;
    use HasSnakeCaseAttributes;
    use CanBeFilledByRequest;

    /**
     * Automatically encrypts password value with the bcrypt() function.
     * Note: The full functionality of this function only works within a Laravel application!
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (!isset($this->attributes)) {
            return;
        }

        if (strlen($value) === 60 && preg_match('/^\$2y\$/', $value)) {
            $this->attributes['password'] = $value;
        } else {
            if (function_exists('bcrypt')) {
                $this->attributes['password'] = bcrypt($value);
            }
        }
    }

    /**
     * @return array
     */
    public function roleNames(): array
    {
        return $this->getRoleNames()->toArray();
    }
}
