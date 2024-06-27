<?php

namespace App\Http\Controllers;

use App\Models\BaiThi;
use App\Models\KetQua;
use App\Models\MonHoc;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\NguoiDung;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;

class XemDiemController extends Controller
{
    public function index($id) {
        $sinhVien = SinhVien::find($id);
        $tenSinhVien = $sinhVien->ten_sinh_vien;
        $maSinhVien = $sinhVien->ma_sinh_vien;
        $emailSinhVien = $sinhVien->email;
        
        // Lấy danh sách kết quả của sinh viên có mã $maSinhVien
        $ketQuas = KetQua::where('ma_sinh_vien', $maSinhVien)->get();
        $ketQuaArray = [];
        
        // Kiểm tra nếu không có kết quả nào
        if ($ketQuas->isEmpty()) {
            return view('sinhvien.xem-diem.index', [
                'title' => 'Xem điểm',
                'tenSinhVien' => $tenSinhVien,
                'id' => $id,
                'message' => "Sinh viên chưa có điểm."
            ]);
        }
        
        foreach ($ketQuas as $ketQua) {
            // Kiểm tra trạng thái của kết quả
            if ($ketQua->state === 'Public') {
                $maBaiThi = $ketQua->ma_bai_thi;
                
                // Lấy thông tin từ cột bai_thi của bảng SinhVien
                $sinhVienBaiThi = SinhVien::whereJsonContains('state', ['ma_bai_thi' => $maBaiThi])->first();
               
                if ($sinhVienBaiThi) {
                    // Trích xuất thông tin bài thi từ cột bai_thi nếu tồn tại
                    $baiThiData = json_decode($sinhVienBaiThi->state, true);
                    
                    foreach ($baiThiData as $baiThi) {
                        // Kiểm tra xem ma_bai_thi từ bảng KetQua có tồn tại trong bai_thi của sinh viên
                        
                        if ($baiThi['ma_bai_thi'] === $maBaiThi) {
                            $maLopHocPhan = $baiThi['ma_lop_hoc_phan'];
                            
                            // Lấy thông tin lớp học phần từ bảng LopHocPhan
                            $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();
        
                            // Kiểm tra xem lớp học phần có tồn tại không trước khi thêm vào mảng kết quả
                            if ($lopHocPhan) {
                                // Decode dữ liệu JSON trong cột so_cau_tra_loi_dung và truy cập vào so_cau_dung
                                $ketQuaJson = json_decode($ketQua->so_cau_tra_loi_dung, true); // Decode JSON to associative array
                                $soCauDung = $ketQuaJson[0]['so_cau_dung'] ?? 0;
                                
                                $diemJson = json_decode($ketQua->diem, true); // Decode JSON to associative array
                                $diem = $diemJson[0]['diem'] ?? 0;
                                
                                // Thêm thông tin kết quả vào mảng
                                $ketQuaArray[] = [
                                    'maBaiThi' => $ketQua->ma_bai_thi,
                                    'tenBaiThi' => $ketQua->ten_bai_thi,
                                    'diem' => $diem,
                                    'soCauDung' => $soCauDung,
                                    'maLopHocPhan' => $maLopHocPhan,
                                    'tenLopHocPhan' => $lopHocPhan->ten_lop_hoc_phan, // Thêm tên lớp học phần vào mảng
                                ];
                            }
                        }
                    }
                }
            } else {
                return view('sinhvien.xem-diem.index', [
                    'title' => 'Xem điểm',
                    'tenSinhVien' => $tenSinhVien,
                    'id' => $id,
                    'message' => "Điểm sinh viên hiện tại chưa được công khai."
                ]);
            }
        }
        $nguoiDung = NguoiDung::where('email', $emailSinhVien)->first();
        $sessionData = json_decode($nguoiDung->session_id, true);
        
        return view('sinhvien.xem-diem.index', [
            'title' => 'Xem điểm',
            'tenSinhVien' => $tenSinhVien,
            'id' => $id,
            'ketQuas' => $ketQuaArray,
        ]);
    }    
    
    public function handleThemDiemSinhVien(Request $request) {
        $id = $request->id;
        $sinhVien = SinhVien::find($id);
        $maSinhVien = $sinhVien->ma_sinh_vien;
        // Cập nhật state của sinh viên
        $stateArray = json_decode($sinhVien->state, true);

        foreach ($stateArray as &$stateEntry) {
            if (
                $stateEntry['ma_lop_hoc_phan'] === $request->ma_lop_hoc_phan &&
                $stateEntry['lan_thi'] === $request->lan_thi &&
                $stateEntry['ma_bai_thi'] === $request->ma_bai_thi
            ) {
                $stateEntry['state'] = 'Đã nộp';
            }
        }

        $sinhVien->state = json_encode($stateArray);
        $sinhVien->save();
    
        $ketQua = new KetQua;
        $ketQua->ma_bai_thi = $request->ma_bai_thi;
        $ketQua->ten_bai_thi = $request->ten_bai_thi;
    
        // Decode the existing 'diem' field if it exists, otherwise initialize as an empty array
        $existingDiem = $ketQua->diem ? json_decode($ketQua->diem, true) : [];
        // Append the new diem data
        $existingDiem[] = [
            'lan_thi' => $request->lan_thi,
            'ma_lop_hoc_phan' => $request->ma_lop_hoc_phan,
            'diem' => $request->diem
        ];
        $ketQua->diem = json_encode($existingDiem);
    
        $ketQua->ma_sinh_vien = $maSinhVien;
    
        // Decode the existing 'so_cau_tra_loi_dung' field if it exists, otherwise initialize as an empty array
        $existingSoCauTraLoiDung = $ketQua->so_cau_tra_loi_dung ? json_decode($ketQua->so_cau_tra_loi_dung, true) : [];
        // Append the new so_cau_tra_loi_dung data
        $existingSoCauTraLoiDung[] = $request->so_cau_tra_loi_dung;
        $ketQua->so_cau_tra_loi_dung = json_encode($existingSoCauTraLoiDung);
    
        $ketQua->save();
    
        return response()->json([
            'success'   => true,
            'redirect'   => route('sinh-vien.quan-ly.xem-diem.xem-diem-sinh-vien', ['id' => $id])
        ]);
    }
    

    public function indexXemDiemSinhVienGiangVien($id){
        // Tìm giảng viên dựa trên ID
        $giangVien = GiangVien::find($id);
        $maGiangVien = $giangVien->ma_giang_vien;
    
        // Lấy danh sách lớp học phần chứa giảng viên đó
        $danhSachLopHocPhanGiangVien = LopHocPhan::whereJsonContains('danh_sach_giang_vien', ['ma_giang_vien' => $maGiangVien])->paginate(10);
    
        // Khởi tạo mảng danhSachDuLieu để chứa thông tin cần hiển thị
        $danhSachDuLieu = [];
        // Khởi tạo mảng để lưu lại số lần thi cho mỗi mã bài thi
        $thongTinLanThi = [];
    
        // Biến để kiểm tra xem mã bài thi đã được hiển thị hay chưa
        $daHienThiMaBaiThi = [];
    
        // Lấy danh sách bài thi từ các lớp học phần
        foreach ($danhSachLopHocPhanGiangVien as $lopHocPhan) {
            $danhSachBaiThiLopHocPhan = json_decode($lopHocPhan->danh_sach_bai_thi, true);
    
            if (is_array($danhSachBaiThiLopHocPhan)) {
                foreach ($danhSachBaiThiLopHocPhan as $baiThi) {
                    // Ensure 'ma_bai_thi' exists in $baiThi
                    if (isset($baiThi['ma_bai_thi'])) {
                        // Lấy thông tin về bài thi từ bảng BaiThi
                        $thongTinBaiThi = BaiThi::where('ma_bai_thi', $baiThi['ma_bai_thi'])->first();
    
                        if ($thongTinBaiThi) {
                            // Ensure 'lan_thi' exists in $baiThi
                            $lanThi = $baiThi['lan_thi'] ?? null;
    
                            // Nếu mã bài thi chưa được hiển thị
                            if (!isset($daHienThiMaBaiThi[$baiThi['ma_bai_thi']])) {
                                // Tạo một mảng chứa thông tin của mỗi bài thi
                                $duLieu = [
                                    'id' => $lopHocPhan->id,
                                    'ma_lop_hoc_phan' => $lopHocPhan->ma_lop_hoc_phan,
                                    'ten_lop_hoc_phan' => $lopHocPhan->ten_lop_hoc_phan,
                                    'ma_bai_thi' => $baiThi['ma_bai_thi'],
                                    'ten_bai_thi' => $thongTinBaiThi->ten_bai_thi,
                                    'lan_thi' => $lanThi
                                ];
    
                                // Thêm mảng dữ liệu vào mảng danh sách dữ liệu
                                $danhSachDuLieu[] = $duLieu;
    
                                // Đặt biến đã hiển thị mã bài thi thành true
                                $daHienThiMaBaiThi[$baiThi['ma_bai_thi']] = true;
                            }
                            
                            // Kiểm tra nếu mã bài thi đã tồn tại trong mảng thì chỉ cần thêm lần thi mới vào mảng
                            if (!isset($thongTinLanThi[$baiThi['ma_bai_thi']])) {
                                // Nếu mã bài thi chưa tồn tại, thêm mới vào mảng và thêm lần thi đầu tiên
                                $thongTinLanThi[$baiThi['ma_bai_thi']] = [];
                            }
    
                            // Thêm lần thi vào mảng nếu it exists
                            if ($lanThi !== null) {
                                $thongTinLanThi[$baiThi['ma_bai_thi']][] = $lanThi;
                            }
                        }
                    }
                }
            }
        }
    
        $danhSachDuLieu = collect($danhSachDuLieu);
        // Tạo một đối tượng LengthAwarePaginator từ đối tượng Collection
        $perPage = 10; // Số lượng mục trên mỗi trang
        $page = request()->get('page', 1); // Trang hiện tại
        $danhSachDuLieu = new LengthAwarePaginator(
            $danhSachDuLieu->forPage($page, $perPage), // Dữ liệu cho trang cụ thể
            $danhSachDuLieu->count(), // Tổng số mục
            $perPage, // Số lượng mục trên mỗi trang
            $page, // Trang hiện tại
            ['path' => request()->url(), 'query' => request()->query()] // Các tham số yêu cầu khác
        );
    
        $danhSachMon = MonHoc::all();
        // Khởi tạo mảng chứa tên cột
        $danhSachTenCot = ['Mã lớp học phần', 'Tên lớp học phần', 'Mã bài thi', 'Tên bài thi', 'Lần thi '];
    
        // Trả về view và truyền dữ liệu cần thiết
        return view('giangvien.xem-diem-sinh-vien.index', [
            'title' => 'Xem điểm sinh viên',
            'dataType' => 'xem_diem_sinh_vien_giang_vien',
            'danhSachDuLieu' => $danhSachDuLieu, // Truyền vào mảng chứa thông tin của các bài thi
            'danhSachCot' => $danhSachTenCot, // Truyền vào mảng chứa tên cột
            'id' => $id,
            'id_giang_vien' => $id,
            'giangVien' => $giangVien,
            'danhSachMon' => $danhSachMon,
            'thongTinLanThi' => $thongTinLanThi,
        ]);
    }
    

    public function bangDiemSinhVienGiangVien($id, $maLopHocPhan, $maBaiThi, $lanThi)
    {
        // Tìm giảng viên và mã giảng viên
        $giangVien = GiangVien::find($id);
        $maGiangVien = $giangVien->ma_giang_vien;

        // Lấy danh sách mã sinh viên từ bảng LopHocPhan
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();
        $danhSachSinhVienJson = $lopHocPhan->danh_sach_sinh_vien;
        $danhSachSinhVien = json_decode($danhSachSinhVienJson, true);

        // Lấy điểm và số câu đúng của sinh viên từ bảng KetQua
        $ketQua = [];

        foreach ($danhSachSinhVien as $sinhVien) {
            $diemSinhVien = KetQua::where('ma_bai_thi', $maBaiThi)
                                    ->where('ma_sinh_vien', $sinhVien['ma_sinh_vien'])
                                    ->first();
                                    
                                    
            if ($diemSinhVien) {
                $diemDetails = json_decode($diemSinhVien->diem, true);
                $soCauTraLoiDungDetails = json_decode($diemSinhVien->so_cau_tra_loi_dung, true);
                $diem = null;
                $soCauDung = null;

                foreach ($diemDetails as $diemDetail) {
                    if ($diemDetail['lan_thi'] == $lanThi) {
                        $diem = $diemDetail['diem'];
                        break;
                    }
                }

                foreach ($soCauTraLoiDungDetails as $soCauTraLoiDungDetail) {
                    if ($soCauTraLoiDungDetail['lan_thi'] == $lanThi) {
                        $soCauDung = $soCauTraLoiDungDetail['so_cau_dung'];
                        break;
                    }
                }

                if ($diem !== null && $soCauDung !== null) {
                    $ketQua[] = [
                        'ma_sinh_vien' => $sinhVien['ma_sinh_vien'],
                        'ten_sinh_vien' => SinhVien::where('ma_sinh_vien', $sinhVien['ma_sinh_vien'])->value('ten_sinh_vien'),
                        'diem' => $diem,
                        'so_cau_tra_loi_dung' => json_encode(['so_cau_dung' => $soCauDung]), // Chuyển đổi lại thành JSON
                    ];
                }
            }
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $currentPageItems = array_slice($ketQua, ($currentPage - 1) * $perPage, $perPage);
        $danhSachSinhVien = new LengthAwarePaginator($currentPageItems, count($ketQua), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        // Trả về view với dữ liệu cần thiết
        return view('giangvien.xem-diem-sinh-vien.bang-diem', [
            'title' => 'Bảng điểm sinh viên',
            'dataType' => 'bang_diem_sinh_vien_giang_vien',
            'id' => $id,
            'giangVien' => $giangVien,
            'danhSachSinhVien' => $danhSachSinhVien,
            'maBaiThi' => $maBaiThi,
            'maLopHocPhan' => $maLopHocPhan,
            'lanThi' => $lanThi,
        ]);
    }
    
    public function publicDiem(Request $request)
    {
        $maBaiThi = $request->input('maBaiThi');
        
        // Cập nhật trạng thái 'state' của các bản ghi trong bảng KetQua
        KetQua::where('ma_bai_thi', $maBaiThi)->update(['state' => 'Public']);
    
        return response()->json(['success' => true, 'message' => 'Điểm đã được public.']);
    }

    public function unpublicDiem(Request $request)
    {
        $maBaiThi = $request->input('maBaiThi');
        
        // Cập nhật trạng thái 'state' của các bản ghi trong bảng KetQua
        KetQua::where('ma_bai_thi', $maBaiThi)->update(['state' => 'Unpublic']);

        return response()->json(['success' => true, 'message' => 'Điểm đã được unpublic.']);
    }


    
    
}
