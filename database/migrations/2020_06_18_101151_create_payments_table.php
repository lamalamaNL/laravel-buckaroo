<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
            $table->float('amount');
            $table->string('currency');
            $table->enum('status', ['open', 'paid', 'failed']);
            $table->string('paymentmethod');
            $table->string('payment_issuer')->nullable();
            $table->string('transactionId')->nullable();
            $table->string('transactionKey')->nullable();
            $table->string('buckaroo_status')->nullable();
            $table->string('redirect_success')->nullable();
            $table->string('redirect_failed')->nullable();
            $table->text('buckaroo_webhook_data')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
