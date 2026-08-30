<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Classes own sections through sections.class_id. Keep the original
        // unique key on (school_id, name); changing it here also breaks the
        // foreign key that MySQL backs with that index.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No schema changes are made in up().
    }
};
