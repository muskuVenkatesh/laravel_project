<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_pilot_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('route_id');
            $table->string('name');
            $table->string('phone');
            $table->string('alt_phone');
            $table->string('license_type');
            $table->string('license_no')->unique();
            $table->date('license_expire');
            $table->enum('life_insurance', ['1', '0']);
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_pilot_details');
    }
};
