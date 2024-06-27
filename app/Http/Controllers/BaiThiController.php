<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;

use App\Models\BaiThi;
use App\Models\KetQua;
use App\Models\MonHoc;
use App\Models\GiangVien;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class BaiThiController extends Controller
{
    public function index(){
        $danhSachSinhVien = BaiThi::paginate(5);
        $columnNames = Schema::getColumnListing('bai_thi');
        $lopHocPhans = LopHocPhan::select('ma_lop_hoc_phan', 'ten_lop_hoc_phan')->get();
        $danhSachTenCot = ['ID','Mã lớp học phần','Mã bài thi', 'Tên bài thi', 'Môn học','Thời gian bắt đầu', 'Thời gian kết thúc', 'Lần thi', 'Mô tả'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {  
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        return view('admin.quan-ly.bai-thi.index', [
            'title' => 'Danh sách bài thi',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachSinhVien,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'danhSachLopHocPhan' => $lopHocPhans,
            'modalCapNhat' => 'modal-cap-nhat-bai-thi',
            'modalThem' => 'modal-them-bai-thi',
            'modalXoa' => 'modal-xoa-bai-thi',
            'dataType' => 'bai_thi',
        ]);
    }

    public function handleCapNhatBaiThi(Request $request) {
        $id = (int)$request->id_bai_thi;
        $baiThi = BaiThi::find($id);
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $request->ma_bai_thi) || ($request->ma_bai_thi !== $baiThi->ma_bai_thi)) {
            $existingMaBaiThi = BaiThi::where('ma_bai_thi', $request->ma_bai_thi)->first();
            if ($existingMaBaiThi) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã bài thi đã tồn tại!'
                ]);
            }
        
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Mã bài thi chỉ được chứa chữ cái và số.'
            ]);
        }
        if ($request->ten_bai_thi !== $baiThi->ten_bai_thi) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_bai_thi)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên bài thi không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }
        if ($baiThi) {
            $baiThi->ma_lop_hoc_phan = $request->ma_lop_hoc_phan;
            $baiThi->ma_bai_thi = $request->ma_bai_thi;
            $baiThi->ten_bai_thi = $request->ten_bai_thi;
            $baiThi->mon_hoc = $request->mon_hoc;
            $baiThi->thoi_gian_bat_dau = $request->thoi_gian_bat_dau;
            $baiThi->thoi_gian_ket_thuc = $request->thoi_gian_ket_thuc;
            $baiThi->lan_thi = $request->lan_thi;
            $baiThi->mo_ta = $request->mo_ta;
            $baiThi->save();
            $request->session()->flash('success_message', 'Cập nhật bài thi thành công!');
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật môn học thành công!',
                'redirect'   => route('admin.quan-ly.bai-thi.quan-ly-bai-thi')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }

    public function handleThemBaiThi(Request $request) {
        $baiThis = $request->data;
        
        if ($baiThis) {
            foreach ($baiThis as $baiThiData) {
                if (!is_numeric($baiThiData['lan_thi'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Lần thi phải là một số nguyên.'
                    ]);
                }
                if (empty($baiThiData['ma_bai_thi']) || empty($baiThiData['ten_bai_thi']) || empty($baiThiData['thoi_gian_bat_dau']) || empty($baiThiData['thoi_gian_ket_thuc']) || empty($baiThiData['mo_ta'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Vui lòng điền đầy đủ thông tin cho mỗi bài thi.'
                    ]);
                }
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $baiThiData['ma_bai_thi'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Mã bài thi chỉ được chứa chữ cái và số.'
                    ]);
                }
                if (preg_match('/[^\p{L}\s]/u', $baiThiData['ten_bai_thi'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Tên bài thi không được chứa ký tự đặc biệt và số.'
                    ]);
                }
                if (!strtotime($baiThiData['thoi_gian_bat_dau']) || !strtotime($baiThiData['thoi_gian_ket_thuc'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Thời gian bắt đầu và kết thúc phải đúng định dạng ngày tháng (YYYY-MM-DD HH:MM:SS).'
                    ]);
                }
                // Tìm bài thi với cùng mã bài thi và lần thi
                $existingBaiThi = BaiThi::where('ma_bai_thi', $baiThiData['ma_bai_thi'])
                                        ->where('lan_thi', $baiThiData['lan_thi'])
                                        ->first();
    
                // Nếu bài thi không tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingBaiThi) {
                    $newBaiThi = new BaiThi;
                    $newBaiThi->ma_lop_hoc_phan = $baiThiData['ma_lop_hoc_phan'];
                    $newBaiThi->ma_bai_thi = $baiThiData['ma_bai_thi'];
                    $newBaiThi->ten_bai_thi = $baiThiData['ten_bai_thi'];
                    $newBaiThi->mon_hoc = $baiThiData['mon_hoc'];
                    $newBaiThi->thoi_gian_bat_dau = $baiThiData['thoi_gian_bat_dau'];
                    $newBaiThi->thoi_gian_ket_thuc = $baiThiData['thoi_gian_ket_thuc'];
                    $newBaiThi->lan_thi = $baiThiData['lan_thi'];
                    $newBaiThi->mo_ta = $baiThiData['mo_ta'];
                    $newBaiThi->save();
                }
            }
    
            return response()->json([
                'success' => true,
                'type' => 'success',
                'message' => 'Thêm bài thi thành công!',
                'redirect' => route('admin.quan-ly.bai-thi.quan-ly-bai-thi')
            ]);
        } else {
            
            if (empty($request->ma_bai_thi) || empty($request->ten_bai_thi)) {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
    
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $request->ma_bai_thi)) {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'Mã bài thi chỉ được chứa chữ cái và số.'
                ]);
            }
    
            if (preg_match('/[^\p{L}\s]/u', $request->ten_bai_thi)) {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'Tên bài thi không được chứa ký tự đặc biệt và số.'
                ]);
            }
    
            // Kiểm tra bài thi tồn tại dựa trên mã bài thi và lần thi
            $existingBaiThi = BaiThi::where('ma_bai_thi', $request->ma_bai_thi)
                                    ->where('lan_thi', $request->lan_thi)
                                    ->first();
    
            if (!$existingBaiThi) {
                $baiThi = new BaiThi;
                if ($baiThi) {
                    $baiThi->ma_lop_hoc_phan = $request->ma_lop_hoc_phan;
                    $baiThi->ma_bai_thi = $request->ma_bai_thi;
                    $baiThi->ten_bai_thi = $request->ten_bai_thi;
                    $baiThi->mon_hoc = $request->mon_hoc;
                    $baiThi->thoi_gian_bat_dau = $request->thoi_gian_bat_dau;
                    $baiThi->thoi_gian_ket_thuc = $request->thoi_gian_ket_thuc;
                    $baiThi->lan_thi = $request->lan_thi;
                    $baiThi->mo_ta = $request->mo_ta;
                    $baiThi->save();
                    $request->session()->flash('success_message', 'Thêm bài thi thành công!');
    
                    return response()->json([
                        'success' => true,
                        'type' => 'success',
                        'message' => 'Thêm bài thi thành công!',
                        'redirect' => route('admin.quan-ly.bai-thi.quan-ly-bai-thi')
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Có lỗi xảy ra trong quá trình thêm!'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'type' => 'error',
                    'message' => 'Bài thi với mã này và lần thi đã tồn tại!'
                ]);
            }
        }
    }
    

    public function handleXoaBaiThi(Request $request) {
        $id = (int)$request->id_bai_thi;
        $baiThi = BaiThi::find($id);
        
        if (!$baiThi) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy bài thi để xóa!'
            ]);
        }
        
        $baiThi->delete();
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.bai-thi.quan-ly-bai-thi')
        ]);
    }
    public function handleCauHoi(Request $request, $id) { 
        // Tìm bài thi theo ID
        $baiThi = BaiThi::find($id);

        // Giải mã JSON để lấy danh sách câu hỏi
        $danhSachCauHoi = json_decode($baiThi->danh_sach_cau_hoi, true);

        // Chuyển danh sách câu hỏi thành mảng để truy cập dễ dàng hơn

        return view('admin.quan-ly.bai-thi.cau-hoi', [
            'title' => $baiThi->ten_bai_thi,
            'id' => $id,
            'danhSachCauHoi' => $danhSachCauHoi, // Truyền danh sách câu hỏi vào view
        ]);
    }


    public function handleThemCauHoi(Request $request) {
        $cauHoi = BaiThi::find((int)$request->cauHoiId);
        $maLopHocPhan = $cauHoi->ma_lop_hoc_phan;
        $maBaiThi = $cauHoi->ma_bai_thi;
        $lanThi = $cauHoi->lan_thi;
        
        // Decode danh_sach_cau_hoi JSON thành mảng PHP
        $danhSachCauHoi = json_decode($cauHoi->danh_sach_cau_hoi, true) ?? [];
    
        // Kiểm tra và xử lý $request->data nếu nó là một chuỗi JSON
        if (is_string($request->data)) {
            $requestData = json_decode($request->data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $danhSachCauHoi = $requestData;
            } else {
                // Xử lý lỗi khi decode JSON không thành công (nếu cần)
                return response()->json([
                    'success'   => false,
                    'error'     => 'Invalid JSON format in request data.'
                ]);
            }
        } elseif (is_array($request->data)) {
            $danhSachCauHoi = $request->data;
        } else {
            // Xử lý trường hợp khác nếu cần
            return response()->json([
                'success'   => false,
                'error'     => 'Invalid data format in request.'
            ]);
        }
    
        // Assign the updated danh_sach_cau_hoi back to the $cauHoi object
        $cauHoi->danh_sach_cau_hoi = json_encode($danhSachCauHoi);
    
        // Save the changes
        $cauHoi->save();
    
        // Transform danh_sach_cau_hoi into an array of questions, answers, and correct answers
        $formattedCauHoi = [];
        foreach ($danhSachCauHoi as $item) {
            // Lấy ra đáp án đúng dưới dạng văn bản từ mảng cau_tra_loi
            $dapAnDung = '';
            if (isset($item['dap_an_dung'])) {
                if (is_array($item['dap_an_dung'])) {
                    foreach ($item['dap_an_dung'] as $index) {
                        if (isset($item['cau_tra_loi'][$index])) {
                            $dapAnDung .= $item['cau_tra_loi'][$index] . ', ';
                        }
                    }
                    // Xóa dấu phẩy và khoảng trắng cuối cùng
                    $dapAnDung = rtrim($dapAnDung, ', ');
                } else {
                    // Trường hợp đáp án đúng là một chuỗi (nếu có)
                    $dapAnDung = $item['dap_an_dung'];
                }
            }
    
            $formattedCauHoi[] = [
                'cau_hoi' => $item['cau_hoi'],
                'cau_tra_loi' => $item['cau_tra_loi'],
                'dap_an_dung' => $dapAnDung
            ];
        }
    
        // Lấy danh sách so_cau_tra_loi_dung từ bảng KetQua
        $formattedSoCauTraLoiDung = [];
        $ketQuaList = KetQua::where('ma_bai_thi', $maBaiThi)->get();
        
        foreach ($ketQuaList as $ketQua) {
            $soCauTraLoiDung = json_decode($ketQua->so_cau_tra_loi_dung, true);
            foreach ($soCauTraLoiDung as $item) {
                if ($item['lan_thi'] == $lanThi && $item['ma_lop_hoc_phan'] == $maLopHocPhan) {
                    $item['id'] = $ketQua->id;  // Thêm ma_sinh_vien vào mảng $item
                    $formattedSoCauTraLoiDung[] = $item;
                }
            }
        }
        // Tính điểm cho từng câu hỏi
        $diem = 0;
        $tongSoCauHoi = count($formattedCauHoi);
        foreach ($formattedCauHoi as $key => $cauHoi) {
            $dapAnDung = $cauHoi['dap_an_dung'];
            $dapAnChon = isset($formattedSoCauTraLoiDung[0]['cauTraLoi'][$key]['dapAnChon']) 
                ? $formattedSoCauTraLoiDung[0]['cauTraLoi'][$key]['dapAnChon'] 
                : [];
    
            // Điểm cho mỗi câu hỏi
            $diemCauHoi = 0;
    
            // Nếu câu hỏi có nhiều đáp án
            if (strpos($dapAnDung, ', ') !== false) {
                // Chia đáp án đúng thành mảng
                $dapAnDungArray = explode(', ', $dapAnDung);
                $soLuongDapAnDung = count($dapAnDungArray);
                $diemCauHoi = 10 / $tongSoCauHoi; // Điểm cơ bản cho câu hỏi
                $diemChoMoiDapAn = $diemCauHoi / $soLuongDapAnDung; // Điểm cho mỗi đáp án đúng
    
                foreach ($dapAnDungArray as $dapAn) {
                    if (in_array($dapAn, $dapAnChon)) {
                        $diem += $diemChoMoiDapAn;
                    }
                }
            } else { // Nếu câu hỏi chỉ có một đáp án đúng
                $diemCauHoi = 10 / $tongSoCauHoi;
                if (in_array($dapAnDung, $dapAnChon)) {
                    $diem += $diemCauHoi;
                }
            }
        }
    
        // Làm tròn điểm
        $diem = round($diem, 2);
        $ketQuaFormatted = [];
        foreach ($formattedSoCauTraLoiDung as $ketQua) {
            $sinhVienId = $ketQua['id']; // Lấy id của sinh viên từ formattedSoCauTraLoiDung
            $sinhVienDiem = 0;
        
            // Tính điểm của sinh viên dựa trên câu hỏi họ đã làm
            foreach ($formattedCauHoi as $key => $cauHoi) {
                $dapAnDung = $cauHoi['dap_an_dung'];
                $dapAnChon = isset($ketQua['cauTraLoi'][$key]['dapAnChon']) 
                    ? $ketQua['cauTraLoi'][$key]['dapAnChon'] 
                    : [];
        
                // Điểm cho mỗi câu hỏi
                $diemCauHoi = 0;
        
                // Nếu câu hỏi có nhiều đáp án
                if (strpos($dapAnDung, ', ') !== false) {
                    // Chia đáp án đúng thành mảng
                    $dapAnDungArray = explode(', ', $dapAnDung);
                    $soLuongDapAnDung = count($dapAnDungArray);
                    $diemCauHoi = 10 / $tongSoCauHoi; // Điểm cơ bản cho câu hỏi
                    $diemChoMoiDapAn = $diemCauHoi / $soLuongDapAnDung; // Điểm cho mỗi đáp án đúng
        
                    foreach ($dapAnDungArray as $dapAn) {
                        if (in_array($dapAn, $dapAnChon)) {
                            $sinhVienDiem += $diemChoMoiDapAn;
                        }
                    }
                } else { // Nếu câu hỏi chỉ có một đáp án đúng
                    $diemCauHoi = 10 / $tongSoCauHoi;
                    if (in_array($dapAnDung, $dapAnChon)) {
                        $sinhVienDiem += $diemCauHoi;
                    }
                }
            }
        
            // Làm tròn điểm của sinh viên
            $sinhVienDiem = round($sinhVienDiem, 2);
        
            // Cập nhật điểm vào bảng KetQua
            $ketQuaSinhVien = KetQua::find($sinhVienId);
            if ($ketQuaSinhVien) {
                $diemArray = json_decode($ketQuaSinhVien->diem, true);
                $updated = false;

                foreach ($diemArray as &$item) {
                    if ($item['lan_thi'] == $lanThi && $item['ma_lop_hoc_phan'] == $maLopHocPhan) {
                        $item['diem'] = $sinhVienDiem;
                        $updated = true;
                        break;
                    }
                }

                if (!$updated) {
                    $diemArray[] = [
                        'lan_thi' => $lanThi,
                        'ma_lop_hoc_phan' => $maLopHocPhan,
                        'diem' => $sinhVienDiem,
                    ];
                }

                $ketQuaSinhVien->diem = json_encode($diemArray);
                $ketQuaSinhVien->save();
            }
        }
        
        // Trả về kết quả là JSON
        return response()->json([
            'success'   => true,
            'diem'      => $diem,
            'redirect'  => route('admin.quan-ly.bai-thi.quan-ly-bai-thi')
        ]);
    }
    

    public function indexBaiThiGiangVien($id, $maLopHocPhan){
        $giangVien = GiangVien::find($id);
        $maGiangVien = $giangVien->ma_giang_vien;
        $danhSachBaiThi = BaiThi::where([
            ['ma_nguoi_tao', '=', $maGiangVien],
            ['ma_lop_hoc_phan', '=', $maLopHocPhan]
        ])->paginate(10);
        $columnNames = Schema::getColumnListing('bai_thi');
        $danhSachTenCot = ['ID','Mã lớp học phần','Mã bài thi', 'Tên bài thi', 'Môn học','Thời gian bắt đầu', 'Thời gian kết thúc', 'Lần thi', 'Mô tả'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();
        $maMonHoc = $lopHocPhan->ma_mon_hoc;
        return view('giangvien.bai-thi.index', [
            'title' => 'Danh sách bài thi',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachBaiThi,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-bai-thi',
            'modalThem' => 'modal-them-bai-thi',
            'modalXoa' => 'modal-xoa-bai-thi',
            'dataType' => 'bai_thi_giang_vien',
            'giangVien' => $giangVien,
            'id' => $id,
            'id_giang_vien' => $id,
            'ma_lop_hoc_phan' => $maLopHocPhan,
            'ma_mon_hoc' => $maMonHoc,
        ]);
    }


    public function handleThemBaiThiGiangVien(Request $request) {
        $giangVien = GiangVien::find($request->id_giang_vien);
        $maGiangVien = $giangVien->ma_giang_vien;
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $request->ma_lop_hoc_phan)->first();
        $baiThiGVs = $request->data;
        if($baiThiGVs){
            foreach ($baiThiGVs as $baiThiGVData) {
                if (!is_numeric($baiThiGVData['lan_thi'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Lần thi phải là một số nguyên.'
                    ]);
                }
                if (empty($baiThiGVData['ma_bai_thi']) || empty($baiThiGVData['ten_bai_thi']) || empty($baiThiGVData['thoi_gian_bat_dau']) || empty($baiThiGVData['thoi_gian_ket_thuc']) || empty($baiThiGVData['mo_ta'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Vui lòng điền đầy đủ thông tin cho mỗi bài thi.'
                    ]);
                }
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $baiThiGVData['ma_bai_thi'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Mã bài thi chỉ được chứa chữ cái và số.'
                    ]);
                }
                if (preg_match('/[^\p{L}\s]/u', $baiThiGVData['ten_bai_thi'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Tên bài thi không được chứa ký tự đặc biệt và số.'
                    ]);
                }
                if (!strtotime($baiThiGVData['thoi_gian_bat_dau']) || !strtotime($baiThiGVData['thoi_gian_ket_thuc'])) {
                    return response()->json([
                        'success' => false,
                        'type' => 'error',
                        'message' => 'Thời gian bắt đầu và kết thúc phải đúng định dạng ngày tháng (YYYY-MM-DD HH:MM:SS).'
                    ]);
                }
                $existingBaiThi = BaiThi::where('ma_bai_thi', $baiThiGVData['ma_bai_thi'])->first();
                // Nếu  chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingBaiThi) {
                    $newBaiThi = new BaiThi;
                    $newBaiThi->ma_lop_hoc_phan = $request->ma_lop_hoc_phan;
                    $newBaiThi->ma_bai_thi = $baiThiGVData['ma_bai_thi'];
                    $newBaiThi->ten_bai_thi = $baiThiGVData['ten_bai_thi'];
                    $newBaiThi->mon_hoc = $baiThiData['mon_hoc'];
                    $newBaiThi->thoi_gian_bat_dau = $baiThiGVData['thoi_gian_bat_dau'];
                    $newBaiThi->thoi_gian_ket_thuc = $baiThiGVData['thoi_gian_ket_thuc'];
                    $newBaiThi->mo_ta = $baiThiGVData['mo_ta'];
                    $newBaiThi->lan_thi = $baiThiGVData['lan_thi'];
                    $newBaiThi->ma_nguoi_tao = $maGiangVien;
                    $newBaiThi->save();

                     // Cập nhật danh_sach_bai_thi của lớp học phần
                    $danhSachBaiThi = json_decode($lopHocPhan->danh_sach_bai_thi, true);
                    $danhSachBaiThi[] = [
                        'ma_bai_thi' => $newBaiThi->ma_bai_thi,
                        'lan_thi' => $newBaiThi->lan_thi
                    ];
                    $lopHocPhan->danh_sach_bai_thi = json_encode($danhSachBaiThi);
                    $lopHocPhan->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm bài thi thành công!',
                'redirect'   => route('giang-vien.quan-ly.bai-thi.quan-ly-bai-thi-giang-vien', [$giangVien->id, $request->ma_lop_hoc_phan])
            ]);
        }else{

            if (empty($request->ma_bai_thi) || empty($request->ten_bai_thi) ) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
    
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $request->ma_bai_thi)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã bài thi chỉ được chứa chữ cái và số.'
                ]);
            }
    
            if (preg_match('/[^\p{L}\s]/u', $request->ten_bai_thi)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên bài thi không được chứa ký tự đặc biệt và số.'
                ]);
            }
            
            $baiThi = new BaiThi;
            if ($baiThi) {
                $baiThi->ma_lop_hoc_phan = $request->ma_lop_hoc_phan;
                $baiThi->ma_bai_thi = $request->ma_bai_thi;
                $baiThi->ten_bai_thi = $request->ten_bai_thi;
                $baiThi->mon_hoc = $request->mon_hoc;
                $baiThi->thoi_gian_bat_dau = $request->thoi_gian_bat_dau;
                $baiThi->thoi_gian_ket_thuc = $request->thoi_gian_ket_thuc;
                $baiThi->lan_thi = $request->lan_thi;
                $baiThi->mo_ta = $request->mo_ta;
                $baiThi->ma_nguoi_tao = $maGiangVien;
                $baiThi->save();
                 // Cập nhật danh_sach_bai_thi của lớp học phần
                $danhSachBaiThi = json_decode($lopHocPhan->danh_sach_bai_thi, true);
                $danhSachBaiThi[] = [
                    'ma_bai_thi' => $baiThi->ma_bai_thi,
                    'lan_thi' => $baiThi->lan_thi
                ];
                $lopHocPhan->danh_sach_bai_thi = json_encode($danhSachBaiThi);
                $lopHocPhan->save();

                $request->session()->flash('success_message', 'Thêm bài thi thành công!');
    
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm bài thi thành công!',
                    'redirect'   => route('giang-vien.quan-ly.bai-thi.quan-ly-bai-thi-giang-vien', [$giangVien->id, $request->ma_lop_hoc_phan])
                ]);
            } else {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Có lỗi xảy ra trong quá trình thêm!'
                ]);
            }
        }
       
    }

    public function handleCapNhatBaiThiGiangVien(Request $request) {
        $id = (int)$request->id_bai_thi;
        $baiThi = BaiThi::find($id);
        if ($request->ten_bai_thi !== $baiThi->ten_bai_thi) {
            if (preg_match('/[^\p{L}\s\d]/u', $request->ten_bai_thi)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên bài thi không được chứa ký tự đặc biệt.'
                ]);
            }
        }

        $giangVien = GiangVien::find($request->id_giang_vien);
        $maGiangVien = $giangVien->ma_giang_vien;
        if ($baiThi) {
            $baiThi->ma_bai_thi = $request->ma_bai_thi;
            $baiThi->ten_bai_thi = $request->ten_bai_thi;
            $baiThi->mon_hoc = $request->mon_hoc;
            $baiThi->thoi_gian_bat_dau = $request->thoi_gian_bat_dau;
            $baiThi->thoi_gian_ket_thuc = $request->thoi_gian_ket_thuc;
            $baiThi->lan_thi = $request->lan_thi;
            $baiThi->mo_ta = $request->mo_ta;
            $baiThi->save();
            $request->session()->flash('success_message', 'Cập nhật bài thi thành công!');
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật môn học thành công!',
                'redirect'   => route('giang-vien.quan-ly.bai-thi.quan-ly-bai-thi-giang-vien', [$giangVien->id, $request->ma_lop_hoc_phan])
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }

    public function handleXoaBaiThiGiangVien(Request $request) {
        $id = (int)$request->id_bai_thi;
        $baiThi = BaiThi::find($id);
        $giangVien = GiangVien::find($request->id_giang_vien);
        $maGiangVien = $giangVien->ma_giang_vien;
        if (!$baiThi) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy bài thi để xóa!'
            ]);
        }
        
        $baiThi->delete();
        return response()->json([
            'success'   => true,
            'redirect'   => route('giang-vien.quan-ly.bai-thi.quan-ly-bai-thi-giang-vien', [$giangVien->id, $request->ma_lop_hoc_phan])
        ]);
    }

    public function handleCauHoiGiangVien(Request $request, $id, $id_giang_vien) { 
        // Tìm bài thi theo ID
        $baiThi = BaiThi::find($id);

        $giangVien = GiangVien::find($request->id_giang_vien);
        $maGiangVien = $giangVien->ma_giang_vien;

        // Giải mã JSON để lấy danh sách câu hỏi
        $danhSachCauHoi = json_decode($baiThi->danh_sach_cau_hoi, true);

        // Chuyển danh sách câu hỏi thành mảng để truy cập dễ dàng hơn

        return view('giangvien.bai-thi.cau-hoi', [
            'title' => $baiThi->ten_bai_thi,
            'id' => $id,
            'danhSachCauHoi' => $danhSachCauHoi, // Truyền danh sách câu hỏi vào view
            'giangVien' => $giangVien, 
            'id_giang_vien' => $id_giang_vien,
        ]);
    }


    public function handleThemCauHoiGiangVien(Request $request) {
        $cauHoi = BaiThi::find((int)$request->cauHoiId);
        // Check if danh_sach_cau_hoi is null and assign an empty array if it is
        $danhSachCauHoi = $cauHoi->danh_sach_cau_hoi ?? [];
        
        // Update danh_sach_cau_hoi with the new data
        $danhSachCauHoi = $request->data;
        
        // Assign the updated danh_sach_cau_hoi back to the $cauHoi object
        $cauHoi->danh_sach_cau_hoi = $danhSachCauHoi;
        
        // Save the changes
        $cauHoi->save();
        $giangVien = GiangVien::find($request->id_giang_vien);
        return response()->json([
            'success'   => true,
            'redirect'   => route('giang-vien.quan-ly.bai-thi.quan-ly-bai-thi-giang-vien', [$giangVien->id,$cauHoi->ma_lop_hoc_phan])
        ]);
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'template.xlsx');
    }

    public function downloadTemplateBaiThi()
    {
        $file = public_path('templates/bai_thi_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'bai_thi_template.xlsx');
    }
    public function downloadTemplateBaiThiGiangVien()
    {
        $file = public_path('templates/bai_thi_giang_vien_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'bai_thi_giang_vien_template.xlsx');
    }

    public function indexPhanCong($maLopHocPhan){
        $danhSachGiangVien = GiangVien::all();
        // Truy vấn bảng BaiThi để lấy danh sách bài thi có ma_lop_hoc_phan được truyền vào
        $danhSachBaiThi = BaiThi::where('ma_lop_hoc_phan', $maLopHocPhan)->paginate(10);

        // Lấy thông tin lớp học phần để hiển thị trong view
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();

        // Trả về view và truyền dữ liệu cần thiết cho view
        return view('admin.quan-ly.phan-cong.index', [
            'title' => 'Danh sách bài thi',
            'danhSachBaiThi' => $danhSachBaiThi,
            'lopHocPhan' => $lopHocPhan,
            'danhSachGiangVien' => $danhSachGiangVien,
        ]);
    }

    public function indexPhanCongGiangVien($maLopHocPhan, $maBaiThi, $lanThi) {
        // Tìm lớp học phần từ mã lớp học phần
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();
    
        // Tìm thông tin bài thi từ mã bài thi, mã lớp học phần và lần thi
        $baiThi = BaiThi::where('ma_bai_thi', $maBaiThi)
                        ->where('ma_lop_hoc_phan', $maLopHocPhan)
                        ->where('lan_thi', $lanThi)
                        ->first();
    
        // Nếu không tìm thấy bài thi, có thể xử lý lỗi hoặc trả về trang 404
    
        // Lấy danh sách giảng viên với thông tin phân công
        $giangViens = GiangVien::paginate(5);
    
        // Duyệt từng GiangVien để giải mã và trích xuất dữ liệu phân công
        foreach ($giangViens as $giangVien) {
            $phanCong = json_decode($giangVien->phan_cong, true);
    
            // Kiểm tra nếu $phanCong không phải là mảng, gán cho $assignments là mảng rỗng
            $assignments = is_array($phanCong) ? $phanCong : [];
    
            // Lọc các phân công khớp với $maLopHocPhan và $maBaiThi hiện tại
            $assignments = array_filter($assignments, function ($assignment) use ($maLopHocPhan, $maBaiThi) {
                return $assignment['ma_lop_hoc_phan'] === $maLopHocPhan && $assignment['ma_bai_thi'] === $maBaiThi;
            });
            
            $giangVien->phan_cong = $assignments;
        }
    
        return view('admin.quan-ly.phan-cong.bang-phan-cong', [
            'title' => 'Phân công giảng viên',
            'danhSachgiangVien' => $giangViens,
            'maLopHocPhan' => $maLopHocPhan,
            'maBaiThi' => $maBaiThi,
            'lanThi' => $lanThi,
            'tenBaiThi' => $baiThi->ten_bai_thi, 
            'tenLopHocPhan' => $lopHocPhan->ten_lop_hoc_phan,
            'thoiGianBatDau' => $baiThi->thoi_gian_bat_dau,
            'thoiGianKetThuc' => $baiThi->thoi_gian_ket_thuc,
        ]);
    }
    
    

    public function handleThemGiangVienPhanCong(Request $request)
    {
        // Lấy thông tin từ request
        $maGiangVien = $request->ma_giang_vien;
        $maLopHocPhan = $request->ma_lop_hoc_phan;
        $maBaiThi = $request->ma_bai_thi;
        $lanThi = $request->lan_thi;
        $thoiGianBatDau = $request->thoi_gian_bat_dau;
        $thoiGianKetThuc = $request->thoi_gian_ket_thuc;

        // Kiểm tra giảng viên có tồn tại không
        $giangVien = GiangVien::where('ma_giang_vien', $maGiangVien)->first();
        if (!$giangVien) {
            return response()->json(['success' => false, 'message' => 'Giảng viên không tồn tại.']);
        }

        // Kiểm tra xem giảng viên đã được phân công trước đó hay chưa
        $phanCongGiangVien = $giangVien->phan_cong;
        
        // Kiểm tra và chuyển đổi dữ liệu phân công thành mảng nếu cần
        if (is_string($phanCongGiangVien)) {
            $phanCongGiangVien = json_decode($phanCongGiangVien, true);
        } elseif (!is_array($phanCongGiangVien)) {
            $phanCongGiangVien = [];
        }

        // Nếu đã có phân công trước đó, kiểm tra xem có trùng thời gian không
        foreach ($phanCongGiangVien as $phanCong) {
            // Kiểm tra trùng thời gian bắt đầu và kết thúc
            if ($phanCong['thoi_gian_bat_dau'] === $thoiGianBatDau && $phanCong['thoi_gian_ket_thuc'] === $thoiGianKetThuc) {
                return response()->json(['success' => false, 'message' => "Trùng giờ phân công với bài '{$phanCong['ma_bai_thi']}'."]);
            }
        }

        // Nếu không trùng thời gian, thêm vào phân công giảng viên
        $newPhanCong = [
            'ma_lop_hoc_phan' => $maLopHocPhan,
            'ma_bai_thi' => $maBaiThi,
            'lan_thi' => $lanThi,
            'thoi_gian_bat_dau' => $thoiGianBatDau,
            'thoi_gian_ket_thuc' => $thoiGianKetThuc,
        ];

        // Thêm phân công mới vào mảng phân công của giảng viên
        $phanCongGiangVien[] = $newPhanCong;

        // Lưu lại dữ liệu phân công dưới dạng JSON vào cột phan_cong của giảng viên
        $giangVien->phan_cong = json_encode($phanCongGiangVien);
        $giangVien->save();

        return response()->json(['success' => true, 'redirect' =>route('admin.quan-ly.bai-thi.bang-phan-cong-giang-vien', [$maLopHocPhan, $maBaiThi, $lanThi ])]);
    }

    public function handleXoaGiangVienPhanCong(Request $request)
    {
        // Lấy thông tin từ request
        $maGiangVien = $request->ma_giang_vien;
        $maLopHocPhan = $request->ma_lop_hoc_phan;
        $maBaiThi = $request->ma_bai_thi;
        $lanThi = $request->lan_thi;

        // Kiểm tra giảng viên có tồn tại không
        $giangVien = GiangVien::where('ma_giang_vien', $maGiangVien)->first();
        if (!$giangVien) {
            return response()->json(['success' => false, 'message' => 'Giảng viên không tồn tại.']);
        }

        // Kiểm tra xem giảng viên đã được phân công trong bảng phân công hay chưa
        $phanCongGiangVien = $giangVien->phan_cong;
        
        // Kiểm tra và chuyển đổi dữ liệu phân công thành mảng nếu cần
        if (is_string($phanCongGiangVien)) {
            $phanCongGiangVien = json_decode($phanCongGiangVien, true);
        } elseif (!is_array($phanCongGiangVien)) {
            $phanCongGiangVien = [];
        }

        // Tìm và xóa phân công giảng viên dựa trên mã lớp học phần, mã bài thi và lần thi
        $updatedPhanCong = array_filter($phanCongGiangVien, function ($phanCong) use ($maLopHocPhan, $maBaiThi, $lanThi) {
            return !($phanCong['ma_lop_hoc_phan'] == $maLopHocPhan &&
                    $phanCong['ma_bai_thi'] == $maBaiThi &&
                    $phanCong['lan_thi'] == $lanThi);
        });

        // Kiểm tra xem có thay đổi so với dữ liệu phân công ban đầu không
        if ($updatedPhanCong === $phanCongGiangVien) {
            return response()->json(['success' => false, 'message' => 'Giảng viên không được phân công cho lớp học phần và bài thi này.']);
        }

        // Lưu lại dữ liệu phân công mới vào cột phan_cong của giảng viên
        $giangVien->phan_cong = json_encode(array_values($updatedPhanCong));
        $giangVien->save();

        // Trả về response JSON thông báo xóa thành công
        return response()->json(['success' => true, 'redirect' =>route('admin.quan-ly.bai-thi.bang-phan-cong-giang-vien', [$maLopHocPhan, $maBaiThi, $lanThi ])]);
    }


}

