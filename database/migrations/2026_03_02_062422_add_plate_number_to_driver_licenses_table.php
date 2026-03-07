<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_licenses', function (Blueprint $table) {
            $table->string('plate_number')->nullable()->after('driver_name');
        });
    }

    public function down(): void
    {
        Schema::table('driver_licenses', function (Blueprint $table) {
            $table->dropColumn('plate_number');
        });
    }
};