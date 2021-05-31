<?php

declare(strict_types=1);

namespace Fox\UserManagement\Support;

use Illuminate\Support\Arr;
use Illuminate\Auth\AuthManager;
use Fox\UserManagement\Auth\UserProvider;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;

class UserManagement
{
    /**
     * @param string $authProviderDriverName
     */
    public static function registerUserProvider(string $authProviderDriverName = 'application')
    {
        if (function_exists('app')) {
            /** @var AuthManager $authManager */
            $authManager = app(AuthManager::class);
            $authManager->provider($authProviderDriverName, function ($app) {
                /** @var ApplicationContract $app */

                /** @var Config $config */
                $config = $app->make('config');
                $userProviderName = (string) $config->get('permission.auth.provider', 'users');

                return $app->make(
                    UserProvider::class,
                    [
                        'hasher' => $app->make('hash'),
                        'model' => Arr::wrap($config->get('auth.providers.' . $userProviderName, []))['model'] ?? '\App\User',
                    ]
                );
            });
        }
    }
}
