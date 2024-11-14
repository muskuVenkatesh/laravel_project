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
        Schema::create('admission_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->integer('announcement_id');
            $table->string('application_type');
            $table->integer('academic_year_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('fee_book_no')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('physically_challenge')->nullable();
            $table->enum('neet_applicable', ['yes', 'no'])->nullable()->default('no');
            $table->enum('transport_required', ['yes', 'no'])->nullable()->default('no');
            $table->integer('class_id');
            $table->string('reg_no')->nullable();
            $table->string('emis_no')->nullable();
            $table->string('cse_no')->nullable();
            $table->string('file_no')->nullable();
            $table->string('admission_no')->nullable();
            $table->string('admission_fee')->nullable();
            $table->string('admission_status')->nullable();
            $table->string('application_no')->nullable();
            $table->string('application_fee')->nullable();
            $table->string('application_status')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('joining_quota')->nullable()->nullable();;
            $table->integer('first_lang_id')->nullable();;
            $table->integer('second_lang_id')->nullable();;
            $table->integer('third_lang_id')->nullable();
            $table->text('achievements')->nullable();
            $table->text('area_of_interest')->nullable();
            $table->text('additional_skills')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('last_study_course')->nullable();
            $table->string('last_exam_marks')->nullable();
            $table->text('reason_change')->nullable();
            $table->text('reason_gap')->nullable();
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('cast')->nullable();
            $table->string('nationality')->nullable();
            $table->string('mother_tounge')->nullable();
            $table->string('addhar_card_no')->nullable();
            $table->string('pan_card_no')->nullable();
            $table->text('address')->nullable();;
            $table->string('city')->nullable();;
            $table->string('state')->nullable();;
            $table->string('country')->nullable();;
            $table->integer('pin')->nullable();;
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('status', [0, 1])->default(1);
            $table->string('extra_curricular_activites')->nullable();
            $table->string('school_enquiry')->nullable();
            $table->enum('hostel_required', ['yes', 'no'])->nullable()->default('no');
            $table->string('identification_mark')->nullable();
            $table->string('identification_mark_two')->nullable();
            $table->string('sports')->nullable();
            $table->string('volunteer')->nullable();
            $table->string('quota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_forms');
    }
};
