<?php

namespace App\Http\Controllers;

use App\Models\SinhVien;
use App\Models\GiangVien;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $data = [];
    public function adminHome(){
        $this->data['title'] = 'Trang chủ người admin';
        return view('layouts.master', $this->data);
    }

    public function sinhVienHome($id) {
        $sinhVien = SinhVien::find($id);
        if ($sinhVien) {
            $tenSinhVien = $sinhVien->ten_sinh_vien;
            $this->data['title'] = 'Trang chủ sinh viên';
            $this->data['tenSinhVien'] = $tenSinhVien;
            $this->data['id'] = $id;
            return view('sinhvien.layouts.master', $this->data);
        } 
    }
    
    public function giangVienHome($id) {
        $giangVien = GiangVien::find($id);
        if ($giangVien) {
            $tenGiangVien = $giangVien->ten_giang_vien;
            $this->data['title'] = 'Trang chủ giảng viên';
            $this->data['tenGiangVien'] = $tenGiangVien;
            $this->data['id'] = $id;
            return view('layouts.master', $this->data);
        } 
    }

    public function adminQuanLyGiangVien(){
        $this->data['title'] = 'Quán lý giảng viên';
        return view('admin.quan-ly.giang-vien.index', $this->data);
    }
}
