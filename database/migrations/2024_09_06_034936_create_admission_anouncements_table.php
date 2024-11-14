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
        Schema::create('admission_anouncements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('branch_id');
            $table->integer('school_id');
            $table->unsignedBigInteger('academic_year_id');
            $table->string('application_fee');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('last_submission_date');
            $table->integer('class');
            $table->string('admission_fees');
            $table->string('quota')->nullable();
            $table->integer('seats_available');
            $table->string('exam_required')->default('no');
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_anouncements');
    }
};
