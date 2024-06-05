<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompanyDataMigration extends Migration
{
    private $companies        = 'companies';
    private $healthThresholds = 'health_thresholds';

    public function up()
    {
        Schema::create($this->companies, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('phone');
            $table->string('email');
            $table->string('type')->nullable();
            $table->text('address');
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->useCurrent();
            $table->foreignId('city_id')->nullable();
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();
        });

        Schema::create($this->healthThresholds, function (Blueprint $table) {
            $table->id();
            $table->string('company_id');
            $table->string('name');
            $table->string('code');
            $table->integer('target');
            $table->decimal('ratio');
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();

            $table->index(['code', 'company_id'], 'trheshold_code');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->healthThresholds);
        Schema::dropIfExists($this->companies);
    }
}
