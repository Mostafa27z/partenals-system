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
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('status')->nullable();
            $table->string('offer_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('gcode')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('provider')->nullable();
            $table->string('national_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
