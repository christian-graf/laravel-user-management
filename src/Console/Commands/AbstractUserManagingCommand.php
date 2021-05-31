<?php

declare(strict_types=1);

namespace Fox\UserManagement\Console\Commands;

use Illuminate\Console\Command;
use Fox\UserManagement\Eloquent\Models\Role;
use Illuminate\Contracts\Validation\Validator;
use Fox\UserManagement\Eloquent\Models\Permission;
use Illuminate\Contracts\Validation\Factory as FactoryContract;

abstract class AbstractUserManagingCommand extends Command
{
    protected const RULE_EMAIL = 'email';
    protected const RULE_NAME = 'name';
    protected const RULE_PASSWORD = 'password';
    protected const RULE_PASSWORD_CONFIRMATION = 'password_confirmation';

    /**
     * @var FactoryContract
     */
    private FactoryContract $validatorFactory;

    /**
     * @var string|null
     */
    private ?string $userModelClass = null;

    /**
     * @var string|null
     */
    private ?string $roleModelClass = null;

    /**
     * @var string|null
     */
    private ?string $permissionModelClass = null;

    /**
     * @var array
     */
    private array $validationRules = [
        'email' => 'required|string|email|unique:users,email',
        'name' => 'required|string|max:255',
        // 'password' => 'required|string|min:8|max:255|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed',
        'password' => 'required|string|min:8|max:255',
        'password_confirmation' => 'required|string|same:password',
    ];

    /**
     * EditUser constructor.
     *
     * @param FactoryContract $validatorFactory
     */
    public function __construct(FactoryContract $validatorFactory)
    {
        parent::__construct();
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    protected function getOption(string $name): ?string
    {
        return $this->hasOption($name) && $this->option($name) !== false ? (string) $this->option($name) : null;
    }

    /**
     * @param array    $userData
     * @param array    $rulesToUse   A list of rules which should be used within the validator
     * @param int|null $exceptUserId
     *
     * @return Validator
     */
    protected function getValidator(array $userData, array $rulesToUse, ?int $exceptUserId = null): Validator
    {
        $rules = array_intersect_key($this->validationRules, array_flip($rulesToUse));
        if ($exceptUserId !== null) {
            if (isset($rules['email'])) {
                $rules['email'] .= ',' . $exceptUserId;
            }
        }

        return $this->validatorFactory->make($userData, $rules);
    }

    /**
     * @param array    $data
     * @param array    $rulesToUse   A list of rules which should be used to validate the data
     * @param int|null $exceptUserId
     *
     * @return bool
     */
    protected function validateUserInput(array $data, array $rulesToUse, ?int $exceptUserId = null): bool
    {
        $validator = $this->getValidator($data, $rulesToUse, $exceptUserId);
        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $key => $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function userModelClass(): string
    {
        if ($this->userModelClass === null) {
            $this->userModelClass = '\App\User';
            if (function_exists('config')) {
                $this->userModelClass = config('permission.models.user', $this->userModelClass);
            }
        }

        return $this->userModelClass;
    }

    /**
     * @return string
     */
    protected function roleModelClass(): string
    {
        if ($this->roleModelClass === null) {
            $this->roleModelClass = Role::class;
            if (function_exists('config')) {
                $this->roleModelClass = config('permission.models.role', $this->roleModelClass);
            }
        }

        return $this->roleModelClass;
    }

    /**
     * @return string
     */
    protected function permissionModelClass(): string
    {
        if ($this->permissionModelClass === null) {
            $this->permissionModelClass = Permission::class;
            if (function_exists('config')) {
                $this->permissionModelClass = config('permission.models.permission', $this->permissionModelClass);
            }
        }

        return $this->permissionModelClass;
    }
}
