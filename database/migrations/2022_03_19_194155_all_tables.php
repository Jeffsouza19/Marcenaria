<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('avatar')->default('default.png');
            $table->string('password');
        });
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('cnpj')->unique();
            $table->string('email')->unique();
            $table->string('address');
            $table->string('avatar')->default('default.png');
        });
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('address');
            $table->string('avatar')->default('default.png');
            $table->string('password');
        });
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('cpf')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('address');
            $table->string('occupation');
            $table->string('salary');
            $table->string('avatar')->default('default.png');
            $table->string('password');
        });
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_client')->nullable();
            $table->unsignedBigInteger('id_fitter')->nullable();
            $table->unsignedBigInteger('id_woodworker')->nullable();
            $table->text('description');
            $table->string('environment');
            $table->string('price');
            $table->string('photos');
        });
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('description');
            $table->string('value');
            $table->date('date');
            $table->unsignedBigInteger('id_employees')->nullable();
        });
        Schema::create('inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product');
            $table->unsignedBigInteger('id_invetoryTypes')->nullable();
            $table->integer('quantaty')->default(0);
            $table->unsignedBigInteger('id_provider')->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('inventoryTypes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('inventory_types');
    }
};
