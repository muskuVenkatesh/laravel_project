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
        Schema::create('homework_special_instructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homework_id')->nullable();
            $table->string('student_id')->nullable();
            $table->string('message')->nullable();
            $table->enum('status', ['pending', 'completed', 'in_progress'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_special_instructions');
    }
};
