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
        Schema::create('admission_enquiries', function (Blueprint $table) {
            $table->id();
            $table->integer('announcement_id');
            $table->integer('application_no');
            $table->string('application_fee');
            $table->string('name');
            $table->string('father_name');
            $table->string('contact_no');
            $table->string('email');
            $table->integer('class_applied');
            $table->date('dob');
            $table->date('assesment_date')->nullable();
            $table->integer('second_language');
            $table->integer('course_type');
            $table->string('payment_mode');
            $table->string('admission_status')->default(0);
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_enquiries');
    }
};
