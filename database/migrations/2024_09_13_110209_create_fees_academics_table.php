<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('fees_academics', function (Blueprint $table) {
            $table->id();
            $table->integer('academic_id');
            $table->integer('fee_type');
            $table->float('fees_amount');
            $table->enum('status', ['0', '1'])->default('1');
            $table->softDeletes();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('fees_academics');
    }
};
