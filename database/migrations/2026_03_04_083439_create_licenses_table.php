<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create('licenses', function (Blueprint $table) {

            $table->id();

            $table->string('driver_name');

            $table->string('license_number');

            $table->string('plate_number');

            $table->date('expire_date');
            
            $table->integer('days_left')->nullable(); 

            $table->string('license_image')->nullable();

            $table->string('status')->default('safe');

            $table->timestamps();

        });

    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }

};