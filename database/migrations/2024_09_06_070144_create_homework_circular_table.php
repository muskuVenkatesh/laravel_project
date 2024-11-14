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
        Schema::create('homework_circular', function (Blueprint $table) {
            $table->id();
            $table->string('homework_id')->nullable();
            $table->string('student_id')->nullable();
            $table->string('circular_type');
            $table->string('notification_type');
            $table->text('message');
            $table->string('file')->nullable();
            $table->enum('status', ['0', '1',])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_circular');
    }
};
