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
        Schema::create('admission_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('enquiry_id');
            $table->string('venue')->nullable();
            $table->date('interview_date')->nullable();
            $table->string('comments')->nullable();
            $table->string('schedule_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_schedules');
    }
};
