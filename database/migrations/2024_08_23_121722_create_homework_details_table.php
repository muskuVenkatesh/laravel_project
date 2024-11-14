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
        Schema::create('homework_details', function (Blueprint $table) {
            $table->id();
            $table->integer('homework_id');
            $table->integer('subject_id');
            $table->string('homework_data')->nullable();
            $table->string('classwork_data')->nullable();
            $table->string('books_carry')->nullable();
            $table->string('homework_file')->nullable();
            $table->enum('status', [0,1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_details');
    }
};
