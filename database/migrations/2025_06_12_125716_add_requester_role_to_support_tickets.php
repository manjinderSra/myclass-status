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
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->enum('requester_role', ['Student', 'Teacher', 'School', 'Admin'])->default('Student')->after('student_id');
            $table->string('requester_name')->nullable()->after('requester_role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->dropColumn('requester_role');
            $table->dropColumn('requester_name');
        });
    }
};
