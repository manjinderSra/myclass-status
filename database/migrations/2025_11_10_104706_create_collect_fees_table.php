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
        Schema::create('collect_fees', function (Blueprint $table) {
            $table->id();

            // Foreign key reference to assign_fee table
            $table->unsignedBigInteger('assign_fee_id')->index();

            // Fee collection details
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0);

            // New fields
            $table->date('collection_date')->nullable(); // when fee was collected
            $table->enum('payment_type', ['cash', 'upi', 'cheque', 'bank_transfer'])->nullable(); // mode of payment
            $table->string('payment_reference_no')->nullable(); // transaction id / cheque no / UPI ref
            $table->text('note')->nullable(); // any remarks or notes

            // Payment status
            $table->enum('status', ['unpaid', 'pending', 'paid'])->default('unpaid');

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('assign_fee_id')
                  ->references('id')
                  ->on('assign_fee')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collect_fees');
    }
};
