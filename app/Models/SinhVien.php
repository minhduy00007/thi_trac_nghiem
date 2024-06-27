<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SinhVien extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sinh_vien';

    protected $fillable = [
        'ma_sinh_vien',
        'ten_sinh_vien',
        'so_dien_thoai',
        'email',
        'ngay_sinh',
        'ma_khoa',
        'ma_nganh',
        'state',

    ];
}
