<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * สร้างตาราง delivery_jobs
     * ใช้เก็บ "งานส่งสินค้า"
     */
    public function up(): void
    {
        Schema::create('delivery_jobs', function (Blueprint $table) {

            // primary key
            $table->id();

            // ชื่อลูกค้า
            $table->string('customer');

            // สถานที่ปลายทาง
            $table->string('destination');

            // พิกัด GPS
            $table->decimal('lat',10,7)->nullable();
            $table->decimal('lng',10,7)->nullable();

            // วันที่ต้องส่ง
            $table->date('delivery_date');

            // คนขับที่รับงาน
            $table->foreignId('driver_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // รถที่ใช้
            $table->string('vehicle_plate')->nullable();

            // สถานะงาน
            $table->string('status')->default('pending');

            // เวลาสร้าง
            $table->timestamps();
        });
    }

    /**
     * rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_jobs');
    }
};