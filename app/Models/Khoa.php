<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Khoa extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'khoa';

    protected $fillable = [
        'ma_khoa',
        'ten_khoa',

    ];
}
