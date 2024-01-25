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
        Schema::create('add_student_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users', 'id');
            $table->foreignId('class_id')->constrained('class', 'id');
            $table->integer('total_amount')->default(0);
            $table->integer('paid_amount')->default(0);
            $table->integer('remaining_amount')->default(0);
            $table->string('payment_type')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_student_fees');
    }
};
