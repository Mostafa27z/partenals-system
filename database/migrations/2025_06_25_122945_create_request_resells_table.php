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
        Schema::create('request_resells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->enum('resell_type', ['chip', 'branch']);
            $table->string('old_serial')->nullable(); // غير مطلوب دائمًا
            $table->string('new_serial')->nullable(); // مطلوب إذا كان "chip"
            $table->date('request_date')->nullable();
            $table->string('full_name')->nullable(); // مطلوب فقط في حالة "branch"
            $table->string('national_id')->nullable(); // مطلوب فقط في حالة "branch"
            $table->text('comment')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_resells');
    }
};
