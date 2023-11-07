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
        Schema::table('tbl_spent', function (Blueprint $table) {
            $table->string('status')->default(0)->comment('0: Pending, 1: Paid, 2: Cancel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_spent', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
