<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_vehicles_details', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('branch_id'); 
            $table->string('vehicle_type'); 
            $table->string('vehicle_no')->unique(); 
            $table->integer('capacity'); 
            $table->date('insurance_expire');
            $table->enum('status', ['1', '0'])->default('1'); 
            $table->timestamps(); 
            $table->softDeletes(); 
            
            // Define the foreign key constraint for branch_id
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transport_vehicles_details');
    }
};
