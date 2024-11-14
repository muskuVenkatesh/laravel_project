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
        Schema::create('academic_student_setup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_years_id')->constrained('academic_details')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->integer('student_id');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->enum('status', ['0', '1'])->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_student_setup');
        
    }
};
