<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KetQua extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ket_qua';

    protected $fillable = [
        'ma_bai_thi',
        'ten_bai_thi',
        'ma_sinh_vien',
        'diem',
        'so_cau_tra_loi_dung'
    ];
}
