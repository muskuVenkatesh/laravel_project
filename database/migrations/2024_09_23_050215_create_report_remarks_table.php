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
        Schema::create('report_remarks', function (Blueprint $table) {
            $table->id(); 
            $table->text('name'); 
            $table->string('remarks_by');
            $table->enum('status', ['1', '0'])->default('1'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_remarks');
    }
};
