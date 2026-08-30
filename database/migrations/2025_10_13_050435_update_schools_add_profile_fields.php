<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'tagline')) {
                $table->string('tagline')->nullable()->after('name');
            }
            if (!Schema::hasColumn('schools', 'about')) {
                $table->text('about')->nullable()->after('address');
            }
            if (!Schema::hasColumn('schools', 'website')) {
                $table->string('website')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['tagline', 'about', 'website']);
        });
    }
};

