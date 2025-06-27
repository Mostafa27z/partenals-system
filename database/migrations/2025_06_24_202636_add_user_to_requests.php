<?php

// Migration to add columns to requests table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('requested_by')->nullable()->after('status');
            $table->unsignedBigInteger('done_by')->nullable()->after('requested_by');

            $table->foreign('requested_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('done_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['done_by']);
            $table->dropColumn(['requested_by', 'done_by']);
        });
    }
};
