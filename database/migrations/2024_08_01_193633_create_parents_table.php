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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('parent_uid');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('alt_phone')->nullable();
            $table->string('alt_email')->nullable();
            $table->integer('education')->nullable();
            $table->integer('occupation')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_email')->nullable();
            $table->integer('mother_education')->nullable();
            $table->integer('mother_occupation')->nullable();
            $table->string('mother_annual_income')->nullable();
            $table->string('mother_aadhaar_no')->nullable();
            $table->string('mother_pan_card')->nullable();
            $table->date('mother_dob')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
