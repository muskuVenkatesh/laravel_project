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
        Schema::create('school_branche_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('branch_id');
            $table->string('stud_grade')->nullable();
            $table->integer('reg_start_from')->nullable();
            $table->integer('reg_prefix_digit')->nullable();
            $table->integer('offline_payments')->nullable();
            $table->integer('fees_due_days')->nullable();
            $table->enum('cal_fees_fine', ['0','1'])->nullable();
            $table->enum('status', ['0', '1'])->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_branche_settings');

    }
};
