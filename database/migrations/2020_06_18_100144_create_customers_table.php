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
            $table->string('email');
            $table->string('phone');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('gender'); // response value: [1,2]
            $table->date('birthDate');
            $table->string('street');
            $table->string('houseNumber');
            $table->string('zipcode');
            $table->string('city');
            $table->string('country');
            $table->string('culture');
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
