<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiangVien extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'giang_vien';

    protected $fillable = [
        'ma_giang_vien',
        'ten_giang_vien',
        'so_dien_thoai',
        'bai_kiem_tra_khac',
        'email',
        'ngay_sinh',
        'ma_khoa',
        'ma_nganh',
        'cac_mon_giang_day',
        'phan_cong',

    ];
}
