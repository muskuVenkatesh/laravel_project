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
        Schema::create('exam_grades', function (Blueprint $table) {
            $table->id(); 
            $table->integer('branch_id');
            $table->integer('class_id');
            $table->integer('max_marks');
            $table->integer('min_marks');
            $table->string('name'); 
            $table->enum('status', ['1', '0'])->default('1'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_grades');
    }
};
