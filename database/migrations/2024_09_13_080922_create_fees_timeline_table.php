<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fees_timeline', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('installments')->nullable();
            $table->enum('status', [0, 1])->default(1);
            $table->SoftDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fees_timeline');
    }
};
