<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('exam_results')) {
            return;
        }

        Schema::table('exam_results', function (Blueprint $table) {
            $table->enum('exam_type', ['theory', 'practical'])->after('subject_id')->default('theory');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('exam_results')) {
            return;
        }

        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn('exam_type');
        });
    }
};
