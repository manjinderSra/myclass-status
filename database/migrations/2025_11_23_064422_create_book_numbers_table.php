<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('book_numbers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('book_id');   // FIXED name
            $table->unsignedBigInteger('issued_books_id')->nullable();
            $table->string('book_no');

            // status: return, issued, lost
            $table->enum('status', ['return', 'issued', 'lost'])->default('return');

            $table->timestamps();

            // Foreign keys (optional)
            // $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            // $table->foreign('issued_books_id')->references('id')->on('issued_books')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_numbers');
    }
};
