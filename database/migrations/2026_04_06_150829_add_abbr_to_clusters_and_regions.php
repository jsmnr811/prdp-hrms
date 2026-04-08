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
        Schema::table('clusters', function (Blueprint $table) {
            $table->string('abbr', 10)->nullable()->unique()->after('name');
        });

        Schema::table('regions', function (Blueprint $table) {
            $table->string('abbr', 10)->nullable()->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clusters', function (Blueprint $table) {
            $table->dropColumn('abbr');
        });

        Schema::table('regions', function (Blueprint $table) {
            $table->dropColumn('abbr');
        });
    }
};
