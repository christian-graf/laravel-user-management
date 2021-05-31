<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserMgmtCreatePermissionTables extends Migration
{
    private array $defaultTableNames = [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_permissions',
        'model_has_roles' => 'model_roles',
        'role_has_permissions' => 'role_permissions',
    ];

    private array $defaultColumnNames = [
        'model_morph_key' => 'model_id',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = array_merge(
            $this->defaultTableNames,
            \Illuminate\Support\Arr::wrap(config('permission.table_names', []))
        );
        $columnNames = array_merge(
            $this->defaultColumnNames,
            \Illuminate\Support\Arr::wrap(config('permission.column_names', []))
        );

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->charset('ascii');
            $table->string('guard_name', 128)->charset('ascii');
            $table->timestamps();

            $table->index('name');
            $table->index('guard_name');

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->charset('ascii');
            $table->string('guard_name', 28)->charset('ascii');
            $table->timestamps();

            $table->index('name');
            $table->index('guard_name');

            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->id();
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type']);

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->unique(['permission_id', $columnNames['model_morph_key'], 'model_type']);
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->id();
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type']);

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->unique(['role_id', $columnNames['model_morph_key'], 'model_type']);
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->unique(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = array_merge(
            $this->defaultTableNames,
            \Illuminate\Support\Arr::wrap(config('permission.table_names', []))
        );

        Schema::dropIfExists($tableNames['role_has_permissions']);
        Schema::dropIfExists($tableNames['model_has_roles']);
        Schema::dropIfExists($tableNames['model_has_permissions']);
        Schema::dropIfExists($tableNames['roles']);
        Schema::dropIfExists($tableNames['permissions']);
    }
}
