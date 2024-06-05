<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserMigration extends Migration
{
    private $password_resets   = 'password_resets';
    private $users             = 'users';
    private $contactables      = 'contactables';
    private $roles             = 'roles';
    private $permissions       = 'permissions';
    private $permissionables   = 'permissionables';
    private $logs              = 'logs';
    private $subscribtions     = 'subscribtions';
    private $subscribtionables = 'subscribtionables';

    public function up()
    {
        Schema::create($this->password_resets, function (Blueprint $table) {
            $table->id();
            $table->morphs('resetable', 'resetable');
            $table->timestamp('expires_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create($this->users, function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable();
            $table->string('name', 100);
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('api_token')->nullable();
            $table->string('password');
            $table->text('bio')->nullable();
            $table->string('gender', 20)->default('Pria');
            $table->date('birthdate', 30)->nullable();
            $table->boolean('is_active')->default(1);
            $table->foreignId('role_id')->nullable();
            $table->foreignId('parent_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('type');
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();

            $table->unique(['email', 'type', 'company_id'], 'unique_user');
        });

        Schema::create($this->roles, function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'name'], 'company_role_name');
        });

        Schema::create($this->permissions, function (Blueprint $table) {
            $table->id();
            $table->string('gate', 50)->unique();
        });

        Schema::create($this->permissionables, function (Blueprint $table) {
            $table->foreignId('permission_id');
            $table->morphs('permissionable', 'permissionable');
        });

        Schema::create($this->logs, function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->text('data')->nullable();
            $table->string('method', 20);
            $table->string('path');
            $table->ipAddress('ip_address');
            $table->morphs('logable', 'logable');
            $table->timestamps();
        });

        Schema::create($this->subscribtions, function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create($this->subscribtionables, function (Blueprint $table) {
            $table->foreignId('subscribtion_id');
            $table->morphs('subscribtionable', 'subscribtionable');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->password_resets);
        Schema::dropIfExists($this->users);
        Schema::dropIfExists($this->contactables);
        Schema::dropIfExists($this->permissions);
        Schema::dropIfExists($this->permissionables);
        Schema::dropIfExists($this->logs);
        Schema::dropIfExists($this->subscribtions);
        Schema::dropIfExists($this->subscribtionables);
    }
}
