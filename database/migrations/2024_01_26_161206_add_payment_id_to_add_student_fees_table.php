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
        Schema::table('add_student_fees', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->unique()->after('payment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('add_student_fees', function (Blueprint $table) {
            $table->dropColumn('payment_id');
        });
    }
};
