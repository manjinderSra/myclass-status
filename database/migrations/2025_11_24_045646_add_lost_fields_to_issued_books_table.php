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
        Schema::table('issued_books', function (Blueprint $table) {
            $table->boolean('is_lost')->default(false)->after('is_returned');
            $table->date('lost_date')->nullable()->after('is_lost');
            $table->text('lost_remarks')->nullable()->after('lost_date');
        });
    }

    public function down()
    {
        Schema::table('issued_books', function (Blueprint $table) {
            $table->dropColumn(['is_lost', 'lost_date', 'lost_remarks']);
        });
    }
};
