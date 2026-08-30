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
        if (!Schema::hasTable('exam_schedules')) {
            return;
        }

        Schema::table('exam_schedules', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('exam_schedules', 'exam_cancel')) {
                $table->dropColumn('exam_cancel');
            }
            if (Schema::hasColumn('exam_schedules', 'cancel_reason')) {
                $table->dropColumn('cancel_reason');
            }

            // Add enum status column
            $table->enum('status', ['Active', 'Completed', 'Canceled'])->default('Active')->after('exam_type');

            // Add cancel_reason column
            $table->text('cancel_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('exam_schedules')) {
            return;
        }

        Schema::table('exam_schedules', function (Blueprint $table) {
            $table->dropColumn(['status', 'cancel_reason']);

            // Optional: add old columns back
            $table->boolean('exam_cancel')->default(false)->after('exam_type');
        });
    }
};
