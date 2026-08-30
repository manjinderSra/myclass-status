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
        Schema::create('issued_books', function (Blueprint $table) {
            $table->id();
            $table->string('issue_id')->unique(); // Custom issue ID (IB-YYYYMMDD-XXXX)
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('student_id'); // Student ID
            $table->string('student_name');
            $table->string('student_class')->nullable();
            $table->string('book_id'); // Book ID
            $table->string('book_name');
            $table->string('book_no');
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->boolean('is_returned')->default(false);
            $table->text('issue_remarks')->nullable();
            $table->text('return_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issued_books');
    }
};
