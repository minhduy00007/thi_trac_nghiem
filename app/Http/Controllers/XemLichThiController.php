<?php

namespace App\Http\Controllers;

use App\Models\BaiThi;
use App\Models\GiangVien;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;

class XemLichThiController extends Controller
{
    public function xemLichThi($id)
    {
        // Tìm giảng viên dựa trên id
        $giangVien = GiangVien::findOrFail($id);
        
        // Lấy danh sách phân công từ cột phan_cong của giảng viên
        $phanCong = json_decode($giangVien->phan_cong, true);
        
        // Kiểm tra xem phân công có tồn tại và không rỗng
        if (!empty($phanCong)) {
            // Lấy ra danh sách mã bài thi từ phân công
            $danhSachBaiThi = array_column($phanCong, 'ma_bai_thi');
            
            // Tìm các bài thi dựa trên mã bài thi từ danh sách mã bài thi
            $danhSachBaiThiGv = BaiThi::whereIn('ma_bai_thi', $danhSachBaiThi)->get();
            
            // Truyền danh sách bài thi dưới dạng biến trong hàm view()
            return view('giangvien.lich-thi.index', [
                'title' => 'Xem lịch thi',
                'danh_sach_bai_thi' => $danhSachBaiThiGv,
                'giangVien' => $giangVien
            ]);
        } else {
            // Trường hợp không tìm thấy phân công
            return view('giangvien.lich-thi.index', [
                'title' => 'Xem lịch thi',
                'danh_sach_bai_thi' => [],
                'giangVien' => $giangVien,
                'message' => 'Giảng viên chưa có lịch gác thi'
            ]);
        }
    }
    

}
