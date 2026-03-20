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
    Schema::table('delivery_jobs', function (Blueprint $table) {

        // 🔹 เพิ่มคอลัมน์ distance (float)
        // nullable = อนุญาตให้ว่างได้
        // after = วางต่อจาก destination (เพื่อความเป็นระเบียบ)
        $table->float('distance')->nullable()->after('destination');
    });
}

public function down(): void
{
    Schema::table('delivery_jobs', function (Blueprint $table) {

        // 🔹 เผื่อ rollback (ลบคอลัมน์ออก)
        $table->dropColumn('distance');
    });
}
};
