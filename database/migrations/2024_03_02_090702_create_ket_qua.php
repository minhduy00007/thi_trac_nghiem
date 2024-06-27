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
        Schema::create('ket_qua', function (Blueprint $table) {
            $table->id();
            $table->string('ma_lop_hoc_phan');
            $table->string('ma_bai_thi');
            $table->string('ten_bai_thi');
            $table->string('ma_giang_vien_tao');
            $table->string('ma_sinh_vien');
            $table->float('diem');
            $table->json('danh_sach_cau_hoi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ket_qua');
    }
};
