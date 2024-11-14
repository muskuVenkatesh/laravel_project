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
        Schema::create('lib_book', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('name');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('author');
            $table->float('price');
            $table->string('publisher')->nullable();
            $table->string('isbn13')->unique();
            $table->string('isbn10')->unique()->nullable();
            $table->string('display_name')->nullable();
            $table->date('published_date')->nullable();
            $table->enum('status', ['0','1'])->default('1');
            $table->timestamps();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_book');
    }
};
