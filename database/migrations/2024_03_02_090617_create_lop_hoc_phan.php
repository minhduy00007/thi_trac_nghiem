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
        Schema::create('lop_hoc_phan', function (Blueprint $table) {
            $table->id();
            $table->string('ma_lop_hoc_phan');
            $table->string('ten_mon_hoc');
            $table->string('ma_lop_nganh');
            $table->string('ma_lop_mon');
            $table->string('ma_giang_vien');
            $table->datetime('thoi_gian_bat_dau');
            $table->datetime('thoi_gian_ket_thuc');
            $table->json('danh_sach_sinh_vien');
            $table->json('danh_sach_bai_kiem_tra');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lop_hoc_phan');
    }
};
