<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 10);
            $table->string('currency_name')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique('currency_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_currencies');
    }
};