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
        Schema::create('exam_report_config', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->string('max_marks');
            $table->string('pass_marks');
            $table->enum('is_grade', ['1', '0'])->default('0');
            $table->enum('is_average', ['1', '0'])->default('0');
            $table->enum('add_in_grand', ['1', '0'])->default('0');
            $table->enum('topper_visible', ['1', '0'])->default('0');
            $table->enum('rank_visible', ['1', '0'])->default('0');
            $table->integer('sequence')->default('0');
            $table->integer('internal')->nullable();
            $table->integer('external')->nullable();
            $table->date('lock_report')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eexam_report_config');
    }
};
