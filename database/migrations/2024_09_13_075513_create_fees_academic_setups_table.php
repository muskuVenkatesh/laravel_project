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
        Schema::create('fees_academic_setups', function (Blueprint $table) {
            $table->id();
            $table->integer('school_id');
            $table->integer('branch_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('template_id')->default(1);
            $table->integer('academic_id');
            $table->enum('parent_recipet',[0,1])->default(1);
            $table->float('amount');
            $table->float('discount')->default(0);
            $table->integer('discount_type')->nullable();
            $table->integer('pay_timeline');
            $table->date('pay_timeline_date')->nullable();
            $table->enum('status', [0,1])->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees_academic_setups');
    }
};
