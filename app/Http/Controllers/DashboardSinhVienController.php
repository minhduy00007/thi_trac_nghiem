<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\SinhVien;
use App\Models\NguoiDung;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardSinhVienController extends Controller
{
    public function index($id){
        $sinhVien = SinhVien::find($id);
        $tenSinhVien = $sinhVien->ten_sinh_vien;
        $maSinhVien = $sinhVien->ma_sinh_vien;
        $emailSinhVien = $sinhVien->email;
        $danhSachLopHocPhan = LopHocPhan::all();
        $thongTinLopHocPhan = [];
        // Lấy thông tin người dùng từ bảng NguoiDung dựa trên email của sinh viên
        $nguoiDung = NguoiDung::where('email', $emailSinhVien)->first();
        $sessionData = json_decode($nguoiDung->session_id, true);

        foreach ($danhSachLopHocPhan as $lopHocPhan) {
            $danhSachSinhVien = json_decode($lopHocPhan->danh_sach_sinh_vien, true);
            // Kiểm tra xem mã sinh viên hiện tại có trong danh sách sinh viên của lớp học phần hay không
            if (is_array($danhSachSinhVien) && in_array(['ma_sinh_vien' => $maSinhVien], $danhSachSinhVien)) {
                // Chuyển đổi thời gian kết thúc của lớp học phần từ dạng string sang đối tượng Carbon
                $thoiGianKetThuc = Carbon::parse($lopHocPhan->thoi_gian_ket_thuc);
                // So sánh thời gian kết thúc của lớp học phần với thời gian hiện tại
                if ($thoiGianKetThuc->isFuture()) {
                    // Lấy số lượng bài thi của lớp học phần
                    $soLuongBaiThi = DB::table('lop_hoc_phan')->where('id', $lopHocPhan->id)->count();
                    // Đếm số lượng bài thi đã làm của sinh viên trong lớp học phần
                    $soLuongBaiThiDaLam = $this->demSoLuongBaiThiDaLam($maSinhVien, $lopHocPhan->ma_lop_hoc_phan);
                    // Thêm thông tin lớp học phần vào mảng nếu thời gian kết thúc chưa đến
                    $thongTinLopHocPhan[] = [
                        'ma_lop_hoc_phan' =>  $lopHocPhan->ma_lop_hoc_phan,
                        'ten_lop_hoc_phan' => $lopHocPhan->ten_lop_hoc_phan,
                        'thoi_gian_bat_dau' => $lopHocPhan->thoi_gian_bat_dau,
                        'thoi_gian_ket_thuc' => $lopHocPhan->thoi_gian_ket_thuc,
                        'so_luong_bai_thi' => $soLuongBaiThi,
                        'so_luong_bai_thi_da_lam' => $soLuongBaiThiDaLam,
                    ];
                }
            }
        }
        return view('sinhvien.dashboard.index', [
            'title' => 'Dashboard sinh viên',
            'tenSinhVien' => $tenSinhVien,
            'id' => $id,
            'thongTinLopHocPhan' => $thongTinLopHocPhan,
            
        ]);
    }

    private function demSoLuongBaiThiDaLam($maSinhVien, $maLopHocPhan)
    {
        // Lấy dữ liệu từ cột bai_thi của sinh viên
        $sinhVien = SinhVien::where('ma_sinh_vien', $maSinhVien)->first();
        if ($sinhVien) {
            // Parse JSON từ cột bai_thi để trích xuất mã lớp học phần và mã bài thi
            $baiThi = json_decode($sinhVien->bai_thi, true);
            // Đếm số lượng bài thi đã làm của sinh viên trong mã lớp học phần cụ thể
            $soLuongBaiThiDaLam = collect($baiThi)->where('ma_lop_hoc_phan', $maLopHocPhan)->count();
            return $soLuongBaiThiDaLam;
        }
        return 0;
    }
}
