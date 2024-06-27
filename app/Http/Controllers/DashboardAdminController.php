<?php

namespace App\Http\Controllers;

use App\Models\BaiThi;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Thống kê số lượng sinh viên
        $soLuongSinhVien = SinhVien::count();
        
        // Thống kê số lượng lớp học phần
        $soLuongLopHocPhan = LopHocPhan::count();

        // Thống kê số lượng giảng viên
        $soLuongGiangVien = GiangVien::count();

        // Thống kê số lượng bài thi
        $soLuongBaiThi = BaiThi::count();
        return view('admin.quan-ly.dashboard.index', [
            'title' => 'Admin Dashboard',
            'soLuongSinhVien' => $soLuongSinhVien,
            'soLuongLopHocPhan' => $soLuongLopHocPhan,
            'soLuongGiangVien' => $soLuongGiangVien,
            'soLuongBaiThi' => $soLuongBaiThi,
        ]);
    }
}
