<?php

declare(strict_types=1);

namespace Fox\UserManagement\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as UserProviderContract;

class UserProvider extends EloquentUserProvider implements UserProviderContract
{
    /**
     * {@inheritdoc}
     */
    public function retrieveById($identifier)
    {
        $authenticatable = parent::retrieveById($identifier);
        if ($this->isActive($authenticatable)) {
            return $authenticatable;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByToken($identifier, $token)
    {
        $authenticatable = parent::retrieveByToken($identifier, $token);
        if ($this->isActive($authenticatable)) {
            return $authenticatable;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function retrieveByCredentials(array $credentials)
    {
        $authenticatable = parent::retrieveByCredentials($credentials);
        if ($this->isActive($authenticatable)) {
            return $authenticatable;
        }

        return null;
    }

    /**
     * @param Authenticatable|null $authenticatable
     *
     * @return bool
     */
    protected function isActive(?Authenticatable $authenticatable): bool
    {
        if ($authenticatable !== null && $authenticatable instanceof Model) {
            return $authenticatable->getAttributeValue('active') === true;
        }

        return false;
    }
}
