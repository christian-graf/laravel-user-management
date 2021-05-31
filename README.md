# Associate users with roles and permissions

This package is based on the [spatie/laravel-permission](https://packagist.org/packages/spatie/laravel-permission) package
and provides a lot of useful extensions to use it.

## Installation

Use composer to install and use this package in your project.

Install them with

```bash
composer require "fox/laravel-user-management"
```

and you are ready to go!

### Set up the user provider

This package provides a user provider which checks the user `active` flag.
So, only active users could be login into your application. To use the user provider set them up in your
`AuthServiceProvider` class.

1. Add the following line to the `boot()` method:
    ```php
    \Fox\UserManagement\Support\UserManagement::registerUserProvider('i_am_awesome');
    ```
   > HINT: You can define the name of your user provider as you like
1. Use the registered user provider driver in your `config/auth.php`:
    ```php
    'providers' => [
            'users' => [
                'driver' => 'i_am_awesome',
                'model' => App\User::class,
            ],
        ],
    ```
1. If you wish to use another provider instead of `users`, you have to define them in your `config/permission.php` file.
   > CAUTION: The provided user provider is based on the `Illuminate\Auth\EloquentUserProvider`.
   > If you wish to use another user source, you have to overload them by yourself.

## Usage

### Models

* `Fox\UserManagement\Eloquent\Models\Permission`
* `Fox\UserManagement\Eloquent\Models\Role`
* `Fox\UserManagement\Eloquent\Models\RolePermissions`

and a useful trait to enhance your user model with methods to work with roles and permissions.

* `Fox\UserManagement\Eloquent\Models\UserTrait`

### Artisan console commands

#### permission:role-list

```bash
Description:                                                                                                                                  
  Get a list of all available roles.                                                                                                          
                                                                                                                                              
Usage:                                                                                                                                        
  permission:role-list [options]                                                                                                              
                                                                                                                                              
Options:                                                                                                                                      
      --orderBy[=ORDERBY]                Name of the field to order the list [default: "id"]                                                  
      --orderDirection[=ORDERDIRECTION]  Direction to order the list (ASC or DESC) [default: "ASC"]                                           
  -h, --help                             Display this help message                                                                            
  -q, --quiet                            Do not output any message                                                                            
  -V, --version                          Display this application version                                                                     
      --ansi                             Force ANSI output                                                                                    
      --no-ansi                          Disable ANSI output                                                                                  
  -n, --no-interaction                   Do not ask any interactive question                                                                  
      --env[=ENV]                        The environment the command should run under                                                         
  -v|vv|vvv, --verbose                   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug   
```

You also get all provided commands of the [spatie/laravel-permission](https://packagist.org/packages/spatie/laravel-permission) package:

* permission:cache-reset
* permission:create-permission
* permission:create-role
* permission:show

#### user:create

```bash
Description:
  Create a new application user.

Usage:
  user:create <email> <name>

Arguments:
  email                 Email of the new user
  name                  The name of the new user

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

#### user:list

```bash
Description:
  Get a list of all registered users.

Usage:
  user:list [options]

Options:
      --active                           Show only active users
      --inactive                         Show only inactive/disabled users
      --orderBy[=ORDERBY]                Name of the field to order the list [default: "id"]
      --orderDirection[=ORDERDIRECTION]  Direction to order the list (ASC or DESC) [default: "ASC"]
      --showTimestamps                   Add created and / updated datetime information to the list
  -h, --help                             Display this help message
  -q, --quiet                            Do not output any message
  -V, --version                          Display this application version
      --ansi                             Force ANSI output
      --no-ansi                          Disable ANSI output
  -n, --no-interaction                   Do not ask any interactive question
      --env[=ENV]                        The environment the command should run under
  -v|vv|vvv, --verbose                   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

#### user:edit

```bash
Description:
  Update common user information like email or name.

Usage:
  user:edit <id>

Arguments:
  id                    Id of the designated user

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

#### user:roles

```bash
Description:
  List, assign or remove roles from a user.

Usage:
  user:roles [options] [--] <id>

Arguments:
  id                     Id of the designated user

Options:
      --assign[=ASSIGN]  A list of roles to assign to the designated user, separated by ,
      --remove[=REMOVE]  A list of roles to remove from the designated user, separated by ,
  -h, --help             Display this help message
  -q, --quiet            Do not output any message
  -V, --version          Display this application version
      --ansi             Force ANSI output
      --no-ansi          Disable ANSI output
  -n, --no-interaction   Do not ask any interactive question
      --env[=ENV]        The environment the command should run under
  -v|vv|vvv, --verbose   Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

#### user:set-password

```bash
Description:
  Set a user password.

Usage:
  user:set-password <id>

Arguments:
  id                    Id of the designated user

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

#### user:activate

```bash
Description:
  Activate one or more users.

Usage:
  user:activate <id>...

Arguments:
  id                    Id(s) of the designated user(s)

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

#### user:deactivate

```bash
Description:                                                                                                                
  Deactivate one or more users.                                                                                             
                                                                                                                            
Usage:                                                                                                                      
  user:deactivate <id>...                                                                                                   
                                                                                                                            
Arguments:                                                                                                                  
  id                    Id(s) of the designated user(s)                                                                     
                                                                                                                            
Options:                                                                                                                    
  -h, --help            Display this help message                                                                           
  -q, --quiet           Do not output any message                                                                           
  -V, --version         Display this application version                                                                    
      --ansi            Force ANSI output                                                                                   
      --no-ansi         Disable ANSI output                                                                                 
  -n, --no-interaction  Do not ask any interactive question                                                                 
      --env[=ENV]       The environment the command should run under                                                        
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug  
```

## Development - Getting Started

See the [CONTRIBUTING](CONTRIBUTING.md) file.

## Changelog

See the [CHANGELOG](CHANGELOG.md) file.

## License

See the [LICENSE](LICENSE.md) file.
