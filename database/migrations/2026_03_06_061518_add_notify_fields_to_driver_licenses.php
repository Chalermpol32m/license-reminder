<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('driver_licenses', function (Blueprint $table) {

            $table->boolean('notify_15')->default(false);
            $table->boolean('notify_7')->default(false);
            $table->boolean('notify_3')->default(false);
            $table->boolean('notify_expired')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('driver_licenses', function (Blueprint $table) {

            $table->dropColumn([
                'notify_15',
                'notify_7',
                'notify_3',
                'notify_expired'
            ]);

        });
    }
};