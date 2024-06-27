<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BaiThi;
use App\Models\SinhVien;
use App\Models\NguoiDung;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaiThiSinhVienController extends Controller
{
    public function index($id, $maLop)
    {
        $sinhVien = SinhVien::find($id);
        $tenSinhVien = $sinhVien->ten_sinh_vien;
        $maSinhVien = $sinhVien->ma_sinh_vien;
        $emailSinhVien = $sinhVien->email;
        $danhSachLopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLop)->get();
        $thongTinLopHocPhan = [];
        $thongTinBaiThi = [];
        $nguoiDung = NguoiDung::where('email', $emailSinhVien)->first();
        $sessionData = json_decode($nguoiDung->session_id, true);

        foreach ($danhSachLopHocPhan as $lopHocPhan) {
            $danhSachSinhVien = json_decode($lopHocPhan->danh_sach_sinh_vien, true);
            if (is_array($danhSachSinhVien) && in_array(['ma_sinh_vien' => $maSinhVien], $danhSachSinhVien)) {
                $thoiGianKetThuc = Carbon::parse($lopHocPhan->thoi_gian_ket_thuc);
                if ($thoiGianKetThuc->isFuture()) {
                    $danhSachBaiThi = json_decode($lopHocPhan->danh_sach_bai_thi, true);
                    if (is_array($danhSachBaiThi)) {
                        $count = 0;
                        foreach ($danhSachBaiThi as $baiThi) {
                            if ($baiThi['lan_thi'] == 1) {
                                $count++;
                                if ($count > 2) break; // Thoát vòng lặp nếu đã lấy đủ 2 bài thi
                                $thongTinLopHocPhan[] = [
                                    'ten_lop_hoc_phan' => $lopHocPhan->ten_lop_hoc_phan,
                                    'ma_bai_thi' => $baiThi['ma_bai_thi'],
                                    'thoi_gian_bat_dau' => $lopHocPhan->thoi_gian_bat_dau,
                                    'thoi_gian_ket_thuc' => $lopHocPhan->thoi_gian_ket_thuc,
                                ];
                            }
                        }
                    }
                }
            }
        }

        foreach ($thongTinLopHocPhan as $thongTin) {
            $maBaiThi = $thongTin['ma_bai_thi'];
            $danhSachBaiThi = BaiThi::where('ma_bai_thi', $maBaiThi)->get();

            foreach ($danhSachBaiThi as $baiThi) {
                $thoiGianBatDau = Carbon::parse($baiThi->thoi_gian_bat_dau)->format('l, d/m/Y, H:i');
                $thoiGianKetThuc = Carbon::parse($baiThi->thoi_gian_ket_thuc)->format('l, d/m/Y, H:i');
                $danhSachCauHoi = json_decode($baiThi->danh_sach_cau_hoi, true);
                $tongSoCauHoi = ($danhSachCauHoi !== null) ? count($danhSachCauHoi) : "Chưa có câu hỏi";
                $baiThiLanThu = $baiThi->lan_thi;
                $thongTinBaiThi[] = [
                    'ma_bai_thi' => $maBaiThi,
                    'ten_bai_thi' => $baiThi->ten_bai_thi,
                    'thoi_gian_bat_dau' => $thoiGianBatDau,
                    'thoi_gian_ket_thuc' => $thoiGianKetThuc,
                    'lan_thi' => $baiThiLanThu,
                    'tongSoCauHoi' => $tongSoCauHoi,
                ];
            }
        }

        return view('sinhvien.bai-thi.index', [
            'title' => 'Bài thi sinh viên',
            'tenSinhVien' => $tenSinhVien,
            'id' => $id,
            'thongTinBaiThi' => $thongTinBaiThi,
            'maLopHocPhan' => $maLop,
        ]);
    }


    public function lamBaiThi($id, $maLopHocPhan ,$maBaiThi, $lanThi) {
        $sinhVien = SinhVien::find($id);
        if (!$sinhVien) {
            return redirect()->back()->with('error', 'Không tìm thấy sinh viên');
        }
    
        $tenSinhVien = $sinhVien->ten_sinh_vien;
        $maSinhVien = $sinhVien->ma_sinh_vien;
        $emailSinhVien = $sinhVien->email;
        $nguoiDung = NguoiDung::where('email', $emailSinhVien)->first();
        $sessionData = json_decode($nguoiDung->session_id, true);
    
        // Retrieve the specific exam based on both ma_bai_thi and lan_thi
        $baiThi = BaiThi::where('ma_bai_thi', $maBaiThi)->where('lan_thi', $lanThi)->first();
    
        // Kiểm tra xem bài thi có tồn tại hay không
        if ($baiThi) {
            // Trích xuất thông tin về bài thi
            $tenBaiThi = $baiThi->ten_bai_thi;
            $thoiGianBatDau = $baiThi->thoi_gian_bat_dau;
            $thoiGianKetThuc = $baiThi->thoi_gian_ket_thuc;
            $lanThiThu = $baiThi->lan_thi;
            $moTa = $baiThi->mo_ta;
            $thoiGianBatDauThi = Carbon::parse($baiThi->thoi_gian_bat_dau);
            $thoiGianKetThucThi = Carbon::parse($baiThi->thoi_gian_ket_thuc);
            // Tính thời gian
            $thoiGianLamBai = $thoiGianBatDauThi->diffInMinutes($thoiGianKetThucThi);
            $sogio = floor($thoiGianLamBai / 60);
            $sophut = $thoiGianLamBai % 60;
    
            // Kiểm tra trạng thái của sinh viên trong cột state
            $stateArray = json_decode($sinhVien->state, true);
            if (is_null($stateArray)) {
                $stateArray = [];
            }
    
            $sinhVienState = 'false';
    
            foreach ($stateArray as $stateEntry) {
                if (
                    $stateEntry['ma_lop_hoc_phan'] === $maLopHocPhan &&
                    $stateEntry['lan_thi'] === $lanThi &&
                    $stateEntry['ma_bai_thi'] === $maBaiThi
                ) {
                    if (in_array($stateEntry['state'], ['Đã nộp', 'Đang làm'])) {
                        $sinhVienState = 'true';
                    } else {
                        $sinhVienState = 'false';
                    }
                    break;
                }
            }
    
            // Truyền thông tin vào view
            return view('sinhvien.lam-bai-thi.index', [
                'title' => 'Bài thi',
                'tenSinhVien' => $tenSinhVien,
                'id' => $id,
                'tenBaiThi' => $tenBaiThi,
                'thoiGianBatDau' => $thoiGianBatDau,
                'thoiGianKetThuc' => $thoiGianKetThuc,
                'thoiGianLamBai' => $thoiGianLamBai,
                'thoiGianKetThucThi' => $thoiGianKetThucThi,
                'lanThi' => $lanThiThu,
                'moTa' => $moTa,
                'sogio' => $sogio,
                'sophut' => $sophut,
                'maBaiThi' => $maBaiThi,
                'sinhVienState' => $sinhVienState,
                'maLopHocPhan' => $maLopHocPhan,
            ]);
        } else {
            // Xử lý trường hợp không tìm thấy bài thi
            return redirect()->back()->with('error', 'Không tìm thấy thông tin bài thi');
        }
    }
    
    

    public function lamBaiThiTracNghiem($id, $maLopHocPhan ,$maBaiThi, $lanThi) {
        $sinhVien = SinhVien::find($id);
        $tenSinhVien = $sinhVien->ten_sinh_vien;
        $maSinhVien = $sinhVien->ma_sinh_vien;
        $emailSinhVien = $sinhVien->email;
    
        // Retrieve the specific exam based on both ma_bai_thi and lan_thi
        $baiThi = BaiThi::where('ma_bai_thi', $maBaiThi)->where('lan_thi', $lanThi)->first();
    
        if (!$baiThi) {
            // Handle case where the exam is not found
            return redirect()->back()->with('error', 'Không tìm thấy thông tin bài thi');
        }
    
        $tenBaiThi = $baiThi->ten_bai_thi;
        $thoiGianBatDau = $baiThi->thoi_gian_bat_dau;
        $thoiGianKetThuc = $baiThi->thoi_gian_ket_thuc;
    
         // Cập nhật trạng thái của sinh viên
        $sinhVienState = [
            'ma_lop_hoc_phan' => $maLopHocPhan,
            'lan_thi' => $lanThi,
            'ma_bai_thi' => $maBaiThi,
            'state' => 'Đang làm', // Mặc định là chưa làm
        ];

        // Lấy trạng thái hiện tại của sinh viên
        $currentState = json_decode($sinhVien->state, true);

        // Kiểm tra xem $currentState có giá trị không rỗng và là một mảng không
        if (empty($currentState) || !is_array($currentState)) {
            // Nếu $currentState là rỗng hoặc không phải là một mảng, gán $currentState bằng một mảng trống
            $currentState = [];
        }

        // Kiểm tra xem có thông tin sinhVienState trong currentState không
        $foundDuplicate = false;
        foreach ($currentState as $stateItem) {
            if ($stateItem['ma_lop_hoc_phan'] == $maLopHocPhan &&
                $stateItem['lan_thi'] == $lanThi &&
                $stateItem['ma_bai_thi'] == $maBaiThi) {
                $foundDuplicate = true;
                break;
            }
        }

        // Nếu không tìm thấy thông tin trùng lặp, thêm mới sinhVienState vào currentState
        if (!$foundDuplicate) {
            $currentState[] = $sinhVienState;

            // Cập nhật lại trạng thái
            $sinhVien->state = json_encode($currentState);
            $sinhVien->save();
        }
    
        $thoiGianBatDauThi = Carbon::parse($baiThi->thoi_gian_bat_dau);
        $thoiGianKetThucThi = Carbon::parse($baiThi->thoi_gian_ket_thuc);
    
        // Tính thời gian làm bài
        $thoiGianLamBai = $thoiGianBatDauThi->diffInMinutes($thoiGianKetThucThi);
    
        $danhSachCauHoi = json_decode($baiThi->danh_sach_cau_hoi, true);
        $totalQuestions = count($danhSachCauHoi);
        $totalPages = ceil($totalQuestions / 5);
    
        $maLopHocPhan = null;
        $danhSachBaiThi = DB::table('lop_hoc_phan')
            ->whereJsonContains('danh_sach_bai_thi', ['ma_bai_thi' => $maBaiThi])
            ->pluck('ma_lop_hoc_phan')
            ->first();
    
        if ($danhSachBaiThi) {
            $maLopHocPhan = $danhSachBaiThi;
    
            // Lấy tên lớp học phần
            $tenLopHocPhan = DB::table('lop_hoc_phan')
                ->where('ma_lop_hoc_phan', $maLopHocPhan)
                ->value('ten_lop_hoc_phan');
        }
    
        $nguoiDung = NguoiDung::where('email', $emailSinhVien)->first();
        $sessionData = json_decode($nguoiDung->session_id, true);
    
        return view('sinhvien.lam-bai-thi.lam-bai-thi', [
            'title' => 'Làm bài thi trắc nghiệm',
            'id' => $id,
            'maBaiThi' => $maBaiThi,
            'tenBaiThi' => $tenBaiThi,
            'thoiGianBatDau' => $thoiGianBatDau,
            'thoiGianKetThuc' => $thoiGianKetThuc,
            'thoiGianLamBai' => $thoiGianLamBai,
            'thoiGianKetThucThi' => $thoiGianKetThucThi,
            'maLopHocPhan' => $maLopHocPhan,
            'tenLopHocPhan' => $tenLopHocPhan,
            'danhSachCauHoi' => $danhSachCauHoi,
            'totalQuestions' => $totalQuestions,
            'totalPages' => $totalPages,
            'lanThi' => $lanThi // Add this line to pass the lanThi to the view
        ]);
    }    
    
}
