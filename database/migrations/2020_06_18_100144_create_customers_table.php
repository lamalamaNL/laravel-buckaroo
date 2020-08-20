<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birthDate')->nullable();
            $table->string('street')->nullable();
            $table->string('houseNumber')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('culture')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
