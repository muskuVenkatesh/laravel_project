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
        Schema::create('staff', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');
            $table->string('employee_no')->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('epf_no')->nullable();
            $table->string('uan_no')->nullable();
            $table->string('esi_no')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->date('anniversary_date')->nullable();
            $table->string('spouse_name')->nullable();
            $table->enum('kid_studying', ['yes', 'no'])->default('no');
            $table->string('assigned_activity')->nullable();
            $table->date('joining_date');
            $table->integer('specialized')->nullable();
            $table->integer('department');
            $table->string('work_location')->nullable();
            $table->integer('qualification');
            $table->integer('extra_qualification')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('reason_change')->nullable();
            $table->enum('status', [1, 0])->default(1);
            $table->softDeletes();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            // $table->foreign('specialized')->references('id')->on('subjects')->onDelete('cascade');
            // $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            // $table->foreign('qualification')->references('id')->on('qualifications')->onDelete('cascade');
            // $table->foreign('extra_qualification')->references('id')->on('qualifications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');

    }
};
