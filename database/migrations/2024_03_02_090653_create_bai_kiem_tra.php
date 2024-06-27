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
        Schema::create('bai_kiem_tra', function (Blueprint $table) {
            $table->id();
            $table->string('ma_lop_hoc_phan');
            $table->json('bai_kiem_tra_giua_ky');
            $table->json('bai_kiem_tra_cuoi_ky');
            $table->json('bai_kiem_tra_khac');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bai_kiem_tra');
    }
};
