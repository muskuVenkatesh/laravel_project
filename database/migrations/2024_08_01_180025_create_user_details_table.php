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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->integer('user_id')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            $table->string('cast')->nullable();
            $table->string('image')->nullable();
            $table->integer('mother_tongue')->nullable();
            $table->string('aadhaar_card_no')->nullable();
            $table->string('pan_card_no')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('state')->nullable();
            $table->string('country')->nullable();
            $table->integer('pin')->nullable();
            $table->string('tmp_address')->nullable();
            $table->string('temp_city')->nullable();
            $table->integer('temp_state')->nullable();
            $table->integer('temp_pin')->nullable();
            $table->string('temp_country')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');

    }
};
