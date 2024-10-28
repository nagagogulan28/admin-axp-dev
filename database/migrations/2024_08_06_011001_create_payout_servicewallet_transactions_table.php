<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutServicewalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_servicewallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('apx_bank_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('rrn_no')->nullable();
            $table->unsignedBigInteger('payment_slip_id')->nullable();
            $table->unsignedBigInteger('payment_mode');
            $table->text('remark')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('apx_bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->foreign('payment_slip_id')->references('id')->on('appxpay_documents')->onDelete('set null');
            $table->foreign('payment_mode')->references('id')->on('transaction_modes')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payout_servicewallet_transactions');
    }
}
