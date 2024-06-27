<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Nganh extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'nganh';

    protected $fillable = [
        'ma_nganh',
        'ten_nganh',
        'ma_khoa',
    ];
}
