<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLoginActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_login_activity', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('log_ipaddress'); // Store IP address as string
            $table->string('log_device'); // Device information
            $table->string('log_os'); // Operating system
            $table->string('log_browser'); // Browser information
            $table->timestamp('log_time'); // Login timestamp
            $table->unsignedBigInteger('log_employee'); // Foreign key for employee

            // // Foreign key relationship
            // $table->foreign('log_employee')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_login_activity');
    }
}
