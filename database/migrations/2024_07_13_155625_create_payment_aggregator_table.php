<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {Schema::create('payment_aggregators', function (Blueprint $table) {
        $table->id();
        $table->string('code');
        $table->string('name');
        $table->string('order_prefix');
        $table->double('total_service_fee'); // Changed to double with precision 10, scale 2
        $table->boolean('active')->default(true);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_aggregator');
    }
};
