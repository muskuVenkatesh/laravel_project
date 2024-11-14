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
        Schema::create('certificate_types_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certificate_type_id');
            $table->string('field_label');
            $table->string('field_name');
            $table->string('field_type');
            $table->enum('status', ['0', '1'])->default(1);
            $table->timestamps();
            $table->foreign('certificate_type_id')->references('id')->on('certificate_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_types_fields');
    }
};
