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
        Schema::create('certificate_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_type');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('student_id');
            $table->json('cert_data');
            $table->enum('status', ['0', '1'])->default(1);
            $table->timestamps();


            $table->foreign('certificate_type')->references('id')->on('certificate_types')->onDelete('cascade');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_data');
    }
};
