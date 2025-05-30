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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('email_activation')->nullable();
            $table->string('active_username')->nullable();
            $table->string('active_password')->nullable();
            $table->integer('active_port')->nullable();
            $table->integer('suspension_penalty_days')->nullable();
            $table->integer('allowed_suspension_days')->nullable();
            $table->string('email_problem')->nullable();
            $table->string('problem_username')->nullable();
            $table->string('problem_password')->nullable();
            $table->integer('problem_port')->nullable();
            $table->string('smtp_configuration')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->string('bcc2')->nullable();
            $table->string('portal_username')->nullable();
            $table->string('portal_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
