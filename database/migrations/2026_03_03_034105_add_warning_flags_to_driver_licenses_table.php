<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_licenses', function (Blueprint $table) {
            $table->boolean('notified_14_days')->default(false);
            $table->boolean('notified_7_days')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('driver_licenses', function (Blueprint $table) {
            $table->dropColumn(['notified_14_days', 'notified_7_days']);
        });
    }
};