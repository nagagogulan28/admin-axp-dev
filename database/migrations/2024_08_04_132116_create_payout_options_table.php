<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payout_options', function (Blueprint $table) {
            $table->id();
            $table->string('option_value');
            $table->string('option_name');
            $table->string('unique_model_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payout_options');
    }
};
