<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payout_commission_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->unsignedBigInteger('range_id');
            $table->integer('gst');
            $table->integer('IMPS')->nullable();
            $table->integer('NEFT')->nullable();
            $table->integer('RTGS')->nullable();
            $table->integer('UPI')->nullable();
            $table->integer('PAYTM')->nullable();
            $table->integer('AMAZON')->nullable();
            $table->string('udf1')->nullable();
            $table->string('udf2')->nullable();
            $table->string('udf3')->nullable();
            $table->string('udf4')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payout_commission_charges');
    }
};
