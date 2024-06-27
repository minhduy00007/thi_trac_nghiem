<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonHoc extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'mon_hoc';

    protected $fillable = [
        'ma_mon_hoc',
        'ten_mon_hoc',
        'ma_nganh',
    ];
}
