<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LopHocPhan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lop_hoc_phan';

    protected $fillable = [
        'ma_lop_hoc_phan',
        'ten_mon_hoc',
        'ma_giang_vien',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'danh_sach_sinh_vien',
        'danh_sach_bai_kiem_tra',

    ];
}
