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
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->string('plate_number'); // ทะเบียน
        $table->string('type')->nullable(); // ประเภทรถ
        $table->string('status')->default('available'); // ว่าง / ใช้งาน
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
