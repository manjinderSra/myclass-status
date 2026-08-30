<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; // Added for logging potential issues

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('school_classes', function (Blueprint $table) {
            try {
                // Drop the old unique constraint
                $table->dropUnique('school_classes_school_id_name_unique');
            } catch (\Exception $e) {
                Log::warning("Migration: Could not drop unique index 'school_classes_school_id_name_unique' during up(): " . $e->getMessage());
                // Continue if it fails, as the main goal is to add the new one
            }

            try {
                // Add the new unique constraint including section_id
                $table->unique(['school_id', 'name', 'section_id'], 'school_classes_school_id_name_section_id_unique');
            } catch (\Exception $e) {
                Log::error("Migration: Could not create unique index 'school_classes_school_id_name_section_id_unique' during up(): " . $e->getMessage());
                // If this fails, it's a significant issue
                throw $e; // Re-throw to halt migration if critical part fails
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('school_classes', function (Blueprint $table) {
            try {
                // Drop the new unique constraint created in up()
                $table->dropUnique('school_classes_school_id_name_section_id_unique');
            } catch (\Exception $e) {
                Log::warning("Migration: Could not drop unique index 'school_classes_school_id_name_section_id_unique' during down(): " . $e->getMessage());
            }

            try {
                // Re-add the old unique constraint
                $table->unique(['school_id', 'name'], 'school_classes_school_id_name_unique');
            } catch (\Exception $e) {
                Log::warning("Migration: Could not re-create unique index 'school_classes_school_id_name_unique' during down(): " . $e->getMessage());
                // If this fails, the schema might be left in an inconsistent state for this old constraint
            }
        });

        Schema::enableForeignKeyConstraints();
    }
};
