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
        Schema::create('fees_student_academic_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('branch_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('academic_id');
            $table->integer('fee_academic_id');
            $table->integer('student_id');
            $table->string('fees_details');
            $table->date('due_date');
            $table->float('amount');
            $table->float('discount')->default(0);
            $table->float('amount_to_pay');
            $table->float('amount_paid')->default(0);
            $table->float('balance')->default(0);
            $table->float('fine')->default(0);
            $table->enum('paid_status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->integer('pay_timeline');
            $table->date('pay_timeline_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'overdue']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_student_academic_payments');
    }
};
