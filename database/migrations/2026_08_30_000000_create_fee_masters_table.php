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
        Schema::create('fee_masters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();
            $table->foreignId('fee_group_id')
                ->constrained('fee_groups')
                ->restrictOnDelete();
            $table->foreignId('fee_type_id')
                ->constrained('fee_types')
                ->restrictOnDelete();
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->enum('fine_type', ['None', 'Fixed', 'Percentage'])->default('None');
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index(['school_id', 'fee_group_id', 'fee_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_masters');
    }
};
