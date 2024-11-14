<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportDetailsTable extends Migration
{

    public function up()
    {
        Schema::create('transport_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('student_id');
            $table->integer('vehicle_id');
            $table->integer('pilot_id');
            $table->json('transport');
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transport_details');
    }
}
