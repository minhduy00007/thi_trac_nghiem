<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaiThi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bai_thi';

    protected $fillable = [
        'ma_bai_thi',
        'ten_bai_thi',
        'danh_sach_cau_hoi',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'mo_ta',
        'danh_sach_cau_hoi',
        'ma_nguoi_tao',
        'lan_thi',

    ];
}
