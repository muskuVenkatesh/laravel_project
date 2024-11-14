<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personality_traits', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->unsignedBigInteger('branch_id'); 
            $table->integer('sequence_id'); 
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personality_traits');
    }
};
