<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('request_change_chips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->onDelete('cascade');
            $table->enum('change_type', ['chip', 'branch']); // على الشريحة أو في الفرع
            $table->string('old_serial')->nullable();
            $table->string('new_serial')->nullable(); // required if chip
            $table->date('request_date');
            $table->string('full_name')->nullable();   // only required if branch
            $table->string('national_id', 14)->nullable(); // only required if branch
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_change_chips');
    }
};
