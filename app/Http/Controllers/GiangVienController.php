<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\BaiThi;
use App\Models\KetQua;
use App\Models\MonHoc;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\NguoiDung;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class GiangVienController extends Controller
{
    public function index(){
        $danhSachGiangVien = GiangVien::paginate(10);
        $columnNames = Schema::getColumnListing('giang_vien');
        $danhSachTenCot = ['ID', 'Mã giảng viên', 'Tên giảng viên', 'Số điện thoại', 'Email', 'Ngày sinh', 'Mã khoa','Mã ngành'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        return view('admin.quan-ly.giang-vien.index', [
            'title' => 'Danh sách giảng viên',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachGiangVien,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-giang-vien',
            'modalThem' => 'modal-them-giang-vien',
            'modalXoa' => 'modal-xoa-giang-vien',
            'modalCacMonGiangDay' => 'modal-cac-mon-giang-day',
            'dataType' => 'giang_vien',
            'danhSachMon' => $danhSachMon,
        ]);
    }

    public function handleCapNhatGiangVien(Request $request) {
        $id = (int)$request->id_giang_vien;
        $giangVien = GiangVien::find($id);
        if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_giang_vien) || $request->ma_giang_vien !== $giangVien->ma_giang_vien) {
            $existingMaGiangVien = GiangVien::where('ma_giang_vien', $request->ma_giang_vien)->first();
            if ($existingMaGiangVien) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã giảng viên đã tồn tại!'
                ]);
            }
        
        }

        if ($request->ten_giang_vien !== $giangVien->ten_giang_vien) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_giang_vien)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên giảng viên không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }

        if ($request->so_dien_thoai !== $giangVien->so_dien_thoai || !preg_match('/^\d{10,11}$/', $request->so_dien_thoai)) {
            if (!preg_match('/^\d{10,11}$/', $request->so_dien_thoai)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Số điện thoại phải có từ 10 đến 11 số.'
                ]);
            }
        
            $existingNumberPhone = GiangVien::where('so_dien_thoai', $request->so_dien_thoai)->first();
            if ($existingNumberPhone) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Số điện thoại đã tồn tại!'
                ]);
            }
        }    

        if ($request->email !== $giangVien->email || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Email không đúng định dạng.'
                ]);
            }
        
            $existingEmail = GiangVien::where('email', $request->email)->first();
            if ($existingEmail) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Email đã tồn tại!'
                ]);
            }
        }
        
        if ($request->ngay_sinh !== $giangVien->ngay_sinh) {
            if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $request-> ngay_sinh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Ngày sinh phải có định dạng YYYY-MM-DD.'
                ]);
            }
        }
        if ($giangVien) {
            $giangVien->ma_giang_vien = $request->ma_giang_vien;
            $giangVien->ten_giang_vien = $request->ten_giang_vien;
            $giangVien->so_dien_thoai = $request->so_dien_thoai;
            $giangVien->email = $request->email;
            $giangVien->ngay_sinh = $request->ngay_sinh;
            $giangVien->ma_khoa = $request->ma_khoa;
            $giangVien->ma_nganh = $request->ma_nganh;
            $giangVien->save();
            $request->session()->flash('success_message', 'Cập nhật giảng viên thành công!');
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật giảng viên thành công!',
                'redirect'   => route('admin.quan-ly.giang-vien.quan-ly-giang-vien')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }
    public function handleThemGiangVien(Request $request) {
        $giangViens = $request->data;
        if ($giangViens) {
            foreach ($giangViens as $giangVienData) {
                if (!isset($giangVienData['ma_giang_vien']) || !isset($giangVienData['ten_giang_vien']) || !isset($giangVienData['so_dien_thoai']) || !isset($giangVienData['email']) || !isset($giangVienData['ngay_sinh']) || !isset($giangVienData['ma_khoa']) || !isset($giangVienData['ma_nganh'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Dữ liệu không đúng định dạng.'
                    ]);
                }
                // Kiểm tra mã giảng viên chỉ chứa chữ cái và số
                if (!preg_match('/^[a-zA-Z0-9]+$/', $giangVienData['ma_giang_vien'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Mã giảng viên chỉ được chứa chữ cái và số.'
                    ]);
                }
                // Kiểm tra định dạng số điện thoại
                if (!preg_match('/^\d{10,11}$/', $giangVienData['so_dien_thoai'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Số điện thoại phải có từ 10 đến 11 số.'
                    ]);
                }
                // Kiểm tra định dạng email
                if (!filter_var($giangVienData['email'], FILTER_VALIDATE_EMAIL)) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Email không đúng định dạng.'
                    ]);
                }
                // Kiểm tra định dạng ngày sinh
                if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $giangVienData['ngay_sinh'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Ngày sinh phải có định dạng YYYY-MM-DD.'
                    ]);
                }
                $existingGiangVien = GiangVien::where('ma_giang_vien', $giangVienData['ma_giang_vien'])->first();
                
    
                // Nếu giang viên chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingGiangVien) {
                    $newGiangVien = new GiangVien;
                    $newGiangVien->ma_giang_vien = $giangVienData['ma_giang_vien'];
                    $newGiangVien->ten_giang_vien = $giangVienData['ten_giang_vien'];
                    $newGiangVien->so_dien_thoai = $giangVienData['so_dien_thoai'];
                    $newGiangVien->email = $giangVienData['email'];
                    $newGiangVien->ngay_sinh = $giangVienData['ngay_sinh'];
                    $newGiangVien->ma_khoa = $giangVienData['ma_khoa'];
                    $newGiangVien->ma_nganh = $giangVienData['ma_nganh'];
                    $newGiangVien->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm giảng viên thành công!',
                'redirect'   => route('admin.quan-ly.giang-vien.quan-ly-giang-vien')
            ]);
        }else{
            if (empty($request->ma_giang_vien) || empty($request->ten_giang_vien) || empty($request->so_dien_thoai) || empty($request->email) || empty($request->ngay_sinh) || empty($request->ma_khoa)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_giang_vien)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã giảng viên chỉ được chứa chữ cái và số.'
                ]);
            }
            $existingGiangVien = GiangVien::where('ma_giang_vien', $request->ma_giang_vien)->first();
            if ($existingGiangVien) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã giảng viên đã tồn tại!'
                ]);
            }
            if (preg_match('/[^\p{L}\s]/u', $request->ten_sinh_vien)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên giảng viên không được chứa ký tự đặc biệt và số.'
                ]);
            }
            $existingNumberPhone = GiangVien::where('so_dien_thoai', $request->so_dien_thoai)->first();
            if ($existingNumberPhone) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Số điện thoại đã tồn tại!'
                ]);
            }
            if (!preg_match('/^\d{10,11}$/', $request->so_dien_thoai)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Số điện thoại phải có từ 10 đến 11 số.'
                ]);
            }
            $existingEmail = GiangVien::where('email', $request->email)->first();
            if ($existingEmail) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Email đã tồn tại!'
                ]);
            }
            if (!filter_var($request-> email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Email không đúng định dạng.'
                ]);
            }
            if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $request-> ngay_sinh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Ngày sinh phải có định dạng YYYY-MM-DD.'
                ]);
            }
            $giangVien = new GiangVien;
            if ($giangVien) {
                $giangVien->ma_giang_vien = $request->ma_giang_vien;
                $giangVien->ten_giang_vien = $request->ten_giang_vien;
                $giangVien->so_dien_thoai = $request->so_dien_thoai;
                $giangVien->email = $request->email;
                $giangVien->ngay_sinh = $request->ngay_sinh;
                $giangVien->ma_khoa = $request->ma_khoa;
                $giangVien->ma_nganh = $request->ma_nganh;
                $giangVien->save();
                $request->session()->flash('success_message', 'Thêm giảng viên thành công!');
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm giảng viên thành công!',
                    'redirect'   => route('admin.quan-ly.giang-vien.quan-ly-giang-vien')
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

    public function handleXoaGiangVien(Request $request) {
        $id = (int)$request->id_giang_vien;
        $giangVien = GiangVien::find($id);
        
        if (!$giangVien) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy giảng viên để xóa!'
            ]);
        }
        
        $giangVien->delete();
        return response()->json([
            'success'   => true,
            'message'   => 'Thêm giảng viên thành công!',
            'redirect'   => route('admin.quan-ly.giang-vien.quan-ly-giang-vien')
        ]);
    }

    public function handleCacMonGiangDay(Request $request) {
        $giangVien = GiangVien::find((int)$request->giangVienId);
        $giangVien->cac_mon_giang_day = $request->data;
        $giangVien->save();
        
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.giang-vien.quan-ly-giang-vien')
        ]);
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/giang_vien_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'giang_vien_template.xlsx');
    }

    public function chiTietBaiThi(Request $request)
    {
        // Lấy thông tin từ request
        $maSinhVien = $request->query('maSinhVien');
        $maBaiThi = $request->query('maBaiThi');
        $maLopHocPhan = $request->query('maLopHocPhan');
        $lanThi = $request->query('lanThi');
        
        $baiThi = BaiThi::where('ma_bai_thi', $maBaiThi)
                    ->where('lan_thi', $lanThi)
                    ->first();
        $danhSachCauHoiBaiThi = json_decode($baiThi->danh_sach_cau_hoi, true);
        $totalQuestions = count($danhSachCauHoiBaiThi);
        $totalPages = ceil($totalQuestions / 5);

        $ketQua = KetQua::where('ma_bai_thi', $maBaiThi)
                        ->where('ma_sinh_vien', $maSinhVien)
                        ->first();
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();
        if ($ketQua) {
            // Chuyển đổi dữ liệu JSON thành mảng trong PHP
            $soCauTraLoi = json_decode($ketQua->so_cau_tra_loi_dung, true);

            // Tìm phần tử có lan_thi và ma_lop_hoc_phan khớp
            $cauTraLoi = null;
            foreach ($soCauTraLoi as $entry) {
                if ($entry['lan_thi'] == $lanThi && $entry['ma_lop_hoc_phan'] == $maLopHocPhan) {
                    $cauTraLoi = $entry['cauTraLoi'];
                    break;
                }
            }

            // Kiểm tra nếu $cauTraLoi không phải là mảng thì chuyển đổi nó
            if (!is_array($cauTraLoi)) {
                $cauTraLoi = [];
            }

            return view('giangvien.xem-diem-sinh-vien.chi-tiet-bai-thi', [
                'tenBaiThi' => $baiThi->ten_bai_thi,
                'tenLopHocPhan' => $lopHocPhan->ten_lop_hoc_phan,
                'maLopHocPhan' => $maLopHocPhan,
                'chiTietBaiThi' => $danhSachCauHoiBaiThi,
                'cauTraLoi' => $cauTraLoi,
                'totalQuestions' => $totalQuestions,
                'totalPages' => $totalPages,
            ]);
        }
    }
    public function indexGiamSat($id)
    {
        // Tìm giảng viên dựa trên id
        $giangVien = GiangVien::findOrFail($id);
        $tenGiangVien = $giangVien->ten_giang_vien;
        $maGiangVien = $giangVien->ma_giang_vien;

        // Lấy danh sách phân công từ cột phan_cong của giảng viên
        $phanCong = json_decode($giangVien->phan_cong, true);
        
        // Kiểm tra xem phân công có tồn tại và không rỗng
        if (!empty($phanCong)) {
            $thongTinLopHocPhan = [];
            $currentTime = Carbon::now();
            
            foreach ($phanCong as $phanCongItem) {
                $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $phanCongItem['ma_lop_hoc_phan'])->first();
                
                if ($lopHocPhan) {
                    $thoiGianBatDau = Carbon::parse($lopHocPhan->thoi_gian_bat_dau);
                    $thoiGianKetThuc = Carbon::parse($lopHocPhan->thoi_gian_ket_thuc);

                    // Chỉ lấy các lớp học phần mà thời gian kết thúc vẫn còn hoạt động và giảng viên được truy cập trước 30 phút
                    if ($thoiGianKetThuc->gt($currentTime) && $thoiGianBatDau->subMinutes(30)->lte($currentTime)) {
                        $soLuongBaiThi = BaiThi::where('ma_lop_hoc_phan', $lopHocPhan->ma_lop_hoc_phan)->count();
                        
                        $thongTinLopHocPhan[] = [
                            'ma_lop_hoc_phan' => $lopHocPhan->ma_lop_hoc_phan,
                            'ten_lop_hoc_phan' => $lopHocPhan->ten_lop_hoc_phan,
                            'thoi_gian_bat_dau' => $lopHocPhan->thoi_gian_bat_dau,
                            'thoi_gian_ket_thuc' => $lopHocPhan->thoi_gian_ket_thuc,
                            'so_luong_bai_thi' => $soLuongBaiThi,
                        ];
                    }
                }
            }

            return view('giangvien.theo-doi.index', [
                'title' => 'Lớp học phần',
                'tenGiangVien' => $tenGiangVien,
                'id' => $id,
                'thongTinLopHocPhan' => $thongTinLopHocPhan,
                'giangVien' => $giangVien
            ]);
        } else {
            // Trường hợp không tìm thấy phân công
            return view('giangvien.theo-doi.index', [
                'title' => 'Lớp học phần',
                'tenGiangVien' => $tenGiangVien,
                'id' => $id,
                'thongTinLopHocPhan' => [],
                'giangVien' => $giangVien,
                'message' => 'Giảng viên chưa có lịch gác thi'
            ]);
        }
    }

    
    public function indexBaiThiTheoDoi($id, $maLop)
    {
        // Tìm giảng viên dựa trên id
        $giangVien = GiangVien::findOrFail($id);
        $tenGiangVien = $giangVien->ten_giang_vien;
        $maGiangVien = $giangVien->ma_giang_vien;

        // Lấy danh sách phân công từ cột phan_cong của giảng viên
        $phanCong = json_decode($giangVien->phan_cong, true);

        $thongTinBaiThi = [];

        if (!empty($phanCong)) {
            // Lọc các bài thi của lớp học phần được phân công cho giảng viên
            foreach ($phanCong as $phanCongItem) {
                if ($phanCongItem['ma_lop_hoc_phan'] === $maLop) {
                    $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLop)->first();
                    if ($lopHocPhan) {
                        $thoiGianBatDau = Carbon::parse($lopHocPhan->thoi_gian_bat_dau);
                        $thoiGianKetThuc = Carbon::parse($lopHocPhan->thoi_gian_ket_thuc);

                        // Kiểm tra thời gian hiện tại với thời gian bắt đầu và kết thúc của lớp học phần
                        if ($thoiGianKetThuc->isFuture() && $thoiGianBatDau->subMinutes(30)->isPast()) {
                            $baiThi = BaiThi::where('ma_bai_thi', $phanCongItem['ma_bai_thi'])
                                            ->where('lan_thi', $phanCongItem['lan_thi'])
                                            ->where('ma_lop_hoc_phan', $maLop)
                                            ->first();

                            if ($baiThi) {
                                $danhSachCauHoi = json_decode($baiThi->danh_sach_cau_hoi, true);
                                $tongSoCauHoi = ($danhSachCauHoi !== null) ? count($danhSachCauHoi) : "Chưa có câu hỏi";

                                $thongTinBaiThi[] = [
                                    'ten_bai_thi' => $baiThi->ten_bai_thi,
                                    'ma_bai_thi' => $phanCongItem['ma_bai_thi'],
                                    'thoi_gian_bat_dau' => $phanCongItem['thoi_gian_bat_dau'],
                                    'thoi_gian_ket_thuc' => $phanCongItem['thoi_gian_ket_thuc'],
                                    'lan_thi' => $phanCongItem['lan_thi'],
                                    'tongSoCauHoi' => $tongSoCauHoi,
                                ];
                            }
                        }
                    }
                }
            }
        }

        return view('giangvien.theo-doi.bai-thi-theo-doi', [
            'title' => 'Bài thi',
            'id' => $id,
            'thongTinBaiThi' => $thongTinBaiThi,
            'giangVien' => $giangVien,
            'maLopHocPhan' => $maLop,
        ]);
    }

    
    public function bangTheoDoi($id, $maLopHocPhan, $maBaiThi, $lanThi) {
        
        // Tìm kiếm thông tin lớp học phần có mã tương ứng
        $lopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $maLopHocPhan)->first();
        $baiThi = BaiThi::where('ma_bai_thi', $maBaiThi)
                        ->where('ma_lop_hoc_phan', $maLopHocPhan)
                        ->where('lan_thi', $lanThi)
                        ->first();
        $giangVien = GiangVien::find($id);
        if (!$lopHocPhan || !$baiThi) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin lớp học phần hoặc bài thi');
        }
    
        // Tính toán thời gian kết thúc bài thi và thời gian cho phép cập nhật
        $thoiGianKetThuc = Carbon::parse($baiThi->thoi_gian_ket_thuc);
        $thoiGianKetThuc->addMinutes(5); // Thêm 5 phút cho thời gian kết thúc
        
        // Lấy ra danh sách mã sinh viên từ cột danh_sach_sinh_vien
        $danhSachSinhVien = json_decode($lopHocPhan->danh_sach_sinh_vien, true);
        $maSinhVienArray = array_column($danhSachSinhVien, 'ma_sinh_vien');
    
        // Tìm kiếm tên của sinh viên từ bảng SinhVien
        $sinhVienInfo = SinhVien::whereIn('ma_sinh_vien', $maSinhVienArray)->paginate(10);
    
        $result = [];
        // Kiểm tra và so sánh state của sinh viên với mã lớp học phần, lần thi và mã bài thi
        foreach ($sinhVienInfo as $sinhVien) {
            $state = "Chưa làm"; // Mặc định state là "Chưa làm"
            $currentState = json_decode($sinhVien->state, true);
    
            // Kiểm tra xem $currentState có giá trị là một mảng không
            if (is_array($currentState)) {
                foreach ($currentState as $currentStateItem) {
                    if ($currentStateItem['ma_lop_hoc_phan'] == $maLopHocPhan &&
                        $currentStateItem['lan_thi'] == $lanThi &&
                        $currentStateItem['ma_bai_thi'] == $maBaiThi) {
                        $state = $currentStateItem['state'];
                        break;
                    }
                }
            }
    
            // Thêm hoặc cập nhật state của sinh viên trong danh sách kết quả
            $result[] = [
                'ma_sinh_vien' => $sinhVien->ma_sinh_vien,
                'ten_sinh_vien' => $sinhVien->ten_sinh_vien,
                'state' => $state,
                'coTheCapNhat' => now()->lte($thoiGianKetThuc), // Kiểm tra có thể cập nhật hay không
            ];
        }
        
        // Trả về view với dữ liệu
        return view('giangvien.theo-doi.bang-theo-doi', [
            'result' => $result,
            'sinhVienInfo' => $sinhVienInfo,
            'lanThi' => $lanThi,
            'maBaiThi' => $maBaiThi,
            'id' => $id,
            'maLopHocPhan' => $maLopHocPhan,
            'tenBaiThi' => $baiThi->ten_bai_thi,
            'giangVien' => $giangVien,
            'thoiGianKetThuc' => $thoiGianKetThuc, // Truyền thời gian kết thúc cho view
        ]);
    }
    
    
    
    public function capNhatQuyen(Request $request)
    {
        $id = $request->id;
        $maLopHocPhan = $request->maLopHocPhan;
        $maBaiThi = $request->maBaiThi;
        $lanThi = $request->lanThi;
        $maSinhVien = $request->maSinhVien;
        $backUrl = $request->backUrl; // Lấy tham số backUrl
        // Lấy thông tin giảng viên theo ID
        $giangVien = GiangVien::find($id);
        if (!$giangVien) {
            return redirect($backUrl)->with('error', 'Giảng viên không tồn tại');
        }

        // Lấy thông tin sinh viên theo mã sinh viên
        $sinhVien = SinhVien::where('ma_sinh_vien', $maSinhVien)->first();
        if (!$sinhVien) {
            return redirect($backUrl)->with('error', 'Sinh viên không tồn tại');
        }

        // Phân tích state JSON
        $stateArray = json_decode($sinhVien->state, true);

        // Tìm mục state phù hợp
        $updated = false;
        foreach ($stateArray as &$stateEntry) {
            if ($stateEntry['ma_lop_hoc_phan'] === $maLopHocPhan &&
                $stateEntry['lan_thi'] === $lanThi &&
                $stateEntry['ma_bai_thi'] === $maBaiThi) {

                if (in_array($stateEntry['state'], ['Đã nộp', 'Đang làm'])) {
                    $stateEntry['state'] = 'Chưa làm';
                    $updated = true;
                    break;
                }
            }
        }

        if ($updated) {
            // Cập nhật state trong cơ sở dữ liệu
            $sinhVien->state = json_encode($stateArray);
            $sinhVien->save();

            return redirect($backUrl)->with('success', 'Cập nhật trạng thái thành công');
        } else {
            return redirect($backUrl)->with('error', 'Không tìm thấy trạng thái phù hợp để cập nhật');
        }
    }


}
