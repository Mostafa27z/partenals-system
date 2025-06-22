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
        Schema::create('lines', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->constrained()->onDelete('cascade');
    
    $table->string('phone_number')->unique();
    $table->string('second_phone')->nullable();
    $table->string('provider')->nullable();
    $table->string('status')->nullable();
    $table->string('offer_name')->nullable();
    $table->string('branch_name')->nullable();
    $table->string('employee_name')->nullable();
    $table->string('gcode')->nullable();
    $table->string('line_type')->nullable(); // prepaid / postpaid

    $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('set null');
    $table->string('package')->nullable();
    $table->date('payment_date')->nullable();
    $table->date('last_invoice_date')->nullable();
    $table->text('notes')->nullable();
    
    $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lines');
    }
};
