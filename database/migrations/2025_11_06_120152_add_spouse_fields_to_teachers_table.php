<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('teachers', function (Blueprint $table) {
        $table->string('spouse_type')->nullable(); // W/O or H/O
        $table->string('spouse_name')->nullable()->after('spouse_type');
    });
}

public function down()
{
    Schema::table('teachers', function (Blueprint $table) {
        $table->dropColumn(['spouse_type', 'spouse_name']);
    });
}

};
