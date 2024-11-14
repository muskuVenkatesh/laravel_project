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

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_details')->onDelete('cascade');
            $table->integer('parent_id');
            $table->string('roll_no')->nullable();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('fee_book_no')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->unsignedBigInteger('mother_tongue')->nullable();
            $table->string('physically_challenge')->nullable();
            $table->string('neet_applicable')->nullable();
            $table->string('transport_required')->nullable();
            $table->unsignedBigInteger('medium_id')->nullable()->default(1);
            $table->unsignedBigInteger('class_id')->nullable()->default(1);
            $table->unsignedBigInteger('section_id')->nullable()->default(1);
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('emis_no')->nullable();
            $table->string('cse_no')->nullable();
            $table->string('file_no')->nullable();
            $table->string('admission_no')->nullable();
            $table->date('admission_date');
            $table->string('application_no')->nullable();
            $table->string('joining_quota')->nullable();
            $table->unsignedBigInteger('first_lang_id')->nullable();
            $table->unsignedBigInteger('second_lang_id')->nullable();
            $table->unsignedBigInteger('third_lang_id')->nullable();
            $table->string('achievements')->nullable();
            $table->string('area_of_interest')->nullable();
            $table->string('additional_skills')->nullable();
            $table->string('image')->nullable();
            $table->enum('status',[0,1])->default(1);
            $table->softDeletes();
            $table->timestamps();

    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');

    }
};
