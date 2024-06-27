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
        Schema::create('giang_vien', function (Blueprint $table) {
            $table->id();
            $table->string('ma_giang_vien');
            $table->string('ten_giang_vien');
            $table->string('so_dien_thoai');
            $table->string('email');
            $table->date('ngay_sinh');
            $table->string('ma_khoa');
            $table->string('ma_nganh');
            $table->json('cac_mon_giang_day');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giang_vien');
    }
};
