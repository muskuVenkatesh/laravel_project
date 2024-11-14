<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('id_card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('id_type'); 
            $table->string('name');
            $table->string('file_path');
            $table->string('html_file_path')->nullable();   
            $table->enum('status', ['1', '0'])->default(1); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_card_templates'); 
    }
};
