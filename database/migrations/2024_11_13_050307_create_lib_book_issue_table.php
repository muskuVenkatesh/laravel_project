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
        Schema::create('lib_book_issue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('lib_book')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('staff_user_id')->constrained('users')->onDelete('cascade');
            $table->date('issue_date');
            $table->date('return_date')->nullable();
            $table->foreignId('return_staff_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('return_comments', 255)->nullable();
            $table->enum('return_status', ['1', '0', '2', '3', '4'])
                  ->comment('1 = Issued, 0 = Returned, 2 = Lost, 3 = Not Returned, 4 = Paid');
            $table->timestamps();
            $table->enum('status',['0','1'])->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lib_book_issue');
    }
};
