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
        Schema::create('admission_forms_details', function (Blueprint $table) {
            $table->id();
            $table->integer('admission_id');
            $table->string('father_name');
            $table->string('middle_name')->nullable();
            $table->string('father_last_name');
            $table->string('phone');
            $table->string('father_phone')->nullable();
            $table->string('father_email')->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('father_aadhaar_no')->nullable();
            $table->string('father_pan_card')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_annual_income')->nullable();
            $table->string('mother_aadhaar_no')->nullable();
            $table->string('mother_pan_card')->nullable();
            $table->enum('status', [0, 1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_forms_details');
    }
};
