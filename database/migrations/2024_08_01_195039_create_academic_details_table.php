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
        Schema::create('academic_details', function (Blueprint $table) {
            $table->id();
            $table->year('academic_years');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('academic_description');
            $table->enum('status', ['0', '1'])->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_details');
    }

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
};
