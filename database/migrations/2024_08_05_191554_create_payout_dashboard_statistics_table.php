<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutDashboardStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_dashboard_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('merchant_id');
            $table->integer('total_transactions_count')->default(0);
            $table->integer('total_successfull_transactions')->default(0);
            $table->integer('total_failed_transactions');
            $table->integer('pending_transactions');
            $table->decimal('total_amount_of_successfull_transaction', 10, 2)->default(0.00);
            $table->integer('total_users')->default(0);
            $table->integer('total_live_users')->default(0);
            $table->integer('total_active_users')->default(0);
            $table->decimal('total_gtv', 10, 2)->default(0.00);
            $table->string('terminal_id', 255);
            $table->timestamps(); // This will create 'created_at' and 'updated_at' columns

            // If you need to add foreign key constraints:
            // $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payout_dashboard_statistics');
    }
}
