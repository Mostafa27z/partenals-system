<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('lines', function (Blueprint $table) {
        $table->date('attached_at')->nullable()->after('customer_id');
    });
}

public function down()
{
    Schema::table('lines', function (Blueprint $table) {
        $table->dropColumn('attached_at');
    });
}

};
