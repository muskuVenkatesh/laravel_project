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
        Schema::create('fees_academic_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fees_st_pay_id');
            $table->unsignedBigInteger('student_id');
            $table->string('transaction_id')->nullable();
            $table->float('fees_amount')->nullable();
            $table->float('discount')->nullable();
            $table->float('amount_to_pay')->nullable();
            $table->float('amount_paid')->nullable();
            $table->float('balance')->nullable();
            $table->float('fine')->nullable();
            $table->string('paid_status')->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('status', [0, 1, ])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_academic_payments');
    }
};
