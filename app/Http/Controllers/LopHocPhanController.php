<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\BaiThi;
use App\Models\MonHoc;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\LopHocPhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
class LopHocPhanController extends Controller
{
    public function index(){
        $danhSachLopHocPhan = LopHocPhan::paginate(10);
        $columnNames = Schema::getColumnListing('lop_hoc_phan');
        $danhSachTenCot = ['ID', 'Mã lớp học phần', 'Tên lớp học phần', 'Môn học', 'Thời gian bắt đầu', 'Thời gian kết thúc', 'Danh sách sinh viên', 'Danh sách giảng viên'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot)-2; $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        $danhSachSinhVienAll = SinhVien::all();
        $danhSachGiangVienAll = GiangVien::all();
        $danhSachBaiThiAll = BaiThi::all();
        return view('admin.quan-ly.lop-hoc-phan.index', [
            'title' => 'Danh sách lớp học phần',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachLopHocPhan,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'danhSachSinhVienAll' => $danhSachSinhVienAll,
            'danhSachGiangVienAll' => $danhSachGiangVienAll,
            'danhSachBaiThiAll' => $danhSachBaiThiAll,
            'modalCapNhat' => 'modal-cap-nhat-lop-hoc-phan',
            'modalThem' => 'modal-them-lop-hoc-phan',
            'modalXoa' => 'modal-xoa-lop-hoc-phan',
            'modalSinhVien' => 'modal-sinh-vien',
            'modalGiangVien' => 'modal-giang-vien',
            'modalBaiThi' => 'modal-bai-thi',
            'dataType' => 'lop_hoc_phan',
            
        ]);
    }
    public function handleCapNhatLopHocPhan(Request $request) {
        $id = (int)$request->id_lop_hoc_phan;
        $lop_hoc_phan= LopHocPhan::find($id);

        if ($request->ten_lop_hoc_phan !== $lop_hoc_phan->ten_lop_hoc_phan) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_lop_hoc_phan)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên lớp học phần không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }

        if ($lop_hoc_phan) {
            $lop_hoc_phan->ma_lop_hoc_phan= $request->ma_lop_hoc_phan;
            $lop_hoc_phan->ten_lop_hoc_phan= $request->ten_lop_hoc_phan;
            $lop_hoc_phan->ma_mon_hoc= $request->ma_mon_hoc;
            $lop_hoc_phan->thoi_gian_bat_dau= $request->thoi_gian_bat_dau;
            $lop_hoc_phan->thoi_gian_ket_thuc= $request->thoi_gian_ket_thuc;
            $lop_hoc_phan->save();

            return response()->json([
                'success'   => true,
                'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }
    public function handleThemLopHocPhan(Request $request) {
        $lopHocPhans = $request->data;
        if($lopHocPhans){
            foreach ($lopHocPhans as $lopHocPhanData) {
                if (!isset($lopHocPhanData['ma_lop_hoc_phan']) || !isset($lopHocPhanData['ten_lop_hoc_phan']) || !isset($lopHocPhanData['ma_mon_hoc']) || !isset($lopHocPhanData['thoi_gian_bat_dau']) || !isset($lopHocPhanData['thoi_gian_ket_thuc']) ) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Dữ liệu không đúng định dạng.'
                    ]);
                }
    
                // Kiểm tra mã lớp học phần chỉ chứa chữ cái, số và dấu _
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $lopHocPhanData['ma_lop_hoc_phan'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Mã lớp học phần chỉ được chứa chữ cái, số và dấu _.'
                    ]);
                }
                $existingLopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $lopHocPhanData['ma_lop_hoc_phan'])->first();
    
                // Nếu  chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingLopHocPhan) {
                    $newLopHocPhan = new LopHocPhan;
                    $newLopHocPhan->ma_lop_hoc_phan = $lopHocPhanData['ma_lop_hoc_phan'];
                    $newLopHocPhan->ten_lop_hoc_phan = $lopHocPhanData['ten_lop_hoc_phan'];
                    $newLopHocPhan->ma_mon_hoc = $lopHocPhanData['ma_mon_hoc'];
                    $newLopHocPhan->thoi_gian_bat_dau = $lopHocPhanData['thoi_gian_bat_dau'];
                    $newLopHocPhan->thoi_gian_ket_thuc = $lopHocPhanData['thoi_gian_ket_thuc'];
                    $newLopHocPhan->danh_sach_sinh_vien = json_encode($lopHocPhanData['danh_sach_sinh_vien']);
                    $newLopHocPhan->danh_sach_giang_vien = json_encode($lopHocPhanData['danh_sach_giang_vien']);
                    $newLopHocPhan->danh_sach_bai_thi = json_encode($lopHocPhanData['danh_sach_bai_thi']);
                    $newLopHocPhan->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm lớp học phần thành công!',
                'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
            ]);
        }else{

            if (empty($request->ma_lop_hoc_phan) || empty($request->ten_lop_hoc_phan) || empty($request->ma_mon_hoc) || empty($request->thoi_gian_bat_dau) || empty($request->thoi_gian_ket_thuc) ) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
    
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $request->ma_lop_hoc_phan)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã lớp học phần chỉ được chứa chữ cái, số và dấu _.'
                ]);
            }
            
            $existingLopHocPhan = LopHocPhan::where('ma_lop_hoc_phan', $request->ma_lop_hoc_phan)->first();
            if ($existingLopHocPhan) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã lớp học phần đã tồn tại!'
                ]);
            }
    
            if (preg_match('/[^\p{L}\s]/u', $request->ten_lop_hoc_phan)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên lớp học phần không được chứa ký tự đặc biệt và số.'
                ]);
            }
    
            $lop_hoc_phan= new LopHocPhan;
            if ($lop_hoc_phan) {
                $lop_hoc_phan->ma_lop_hoc_phan= $request->ma_lop_hoc_phan;
                $lop_hoc_phan->ten_lop_hoc_phan= $request->ten_lop_hoc_phan;
                $lop_hoc_phan->ma_mon_hoc= $request->ma_mon_hoc;
                $lop_hoc_phan->thoi_gian_bat_dau= $request->thoi_gian_bat_dau;
                $lop_hoc_phan->thoi_gian_ket_thuc= $request->thoi_gian_ket_thuc;
                $lop_hoc_phan->save();
                $request->session()->flash('success_message', 'Thêm lớp học phần thành công!');
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm lớp học phần thành công!',
                    'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
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

    public function handleXoaLopHocPhan(Request $request) {
        $id = (int)$request->id_lop_hoc_phan;
        $lop_hoc_phan= LopHocPhan::find($id);
        
        // Nếu không tìm thấy giảng viên, trả về thông báo lỗi
        if (!$lop_hoc_phan) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy môn học để xóa!'
            ]);
        }
        
        // Nếu tìm thấy giảng viên, tiến hành xóa
        $lop_hoc_phan->delete();

        // Trả về thông báo thành công và redirect về trang danh sách giảng viên
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
        ]);
    }

    public function handleCapNhatDanhSachSinhVienLopHocPhan(Request $request)
    {
        $lopHocPhan = LopHocPhan::find($request->id);
        $lopHocPhan->danh_sach_sinh_vien = $request->danh_sach_sinh_vien;
        $lopHocPhan->save();
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
        ]);
    }

    public function handleCapNhatDanhSachGiangVienLopHocPhan(Request $request)
    {
        $lopHocPhan = LopHocPhan::find($request->id);
        $lopHocPhan->danh_sach_giang_vien = $request->danh_sach_giang_vien;
        $lopHocPhan->save();
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
        ]);
    }

    public function handleCapNhatDanhSachBaiThiLopHocPhan(Request $request)
    {
        $lopHocPhan = LopHocPhan::find($request->id);
        $lopHocPhan->danh_sach_bai_thi = $request->danh_sach_bai_thi;
        $lopHocPhan->save();
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan')
        ]);
    }

    public function indexLopHocPhanGiangVien($id)
    {
        $giangVien = GiangVien::find($id);
        $maGiangVien = $giangVien->ma_giang_vien;
        $danhSachLopHocPhanGiangVien = LopHocPhan::whereJsonContains('danh_sach_giang_vien',  ['ma_giang_vien' => $maGiangVien])->paginate(10);
        $columnNames = Schema::getColumnListing('lop_hoc_phan');
        $danhSachTenCot = ['ID', 'Mã lớp học phần', 'Tên lớp học phần', 'Môn học', 'Thời gian bắt đầu', 'Thời gian kết thúc', 'Danh sách sinh viên', 'Danh sách giảng viên'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot)-2; $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachSinhVienAll = SinhVien::all();
        $danhSachGiangVienAll = GiangVien::all();
        $danhSachBaiThiAll = BaiThi::all();
        $danhSachMon = MonHoc::all();
        return view('giangvien.lop-hoc-phan.index', [
            'title' => 'Danh sách lớp học phần',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachLopHocPhanGiangVien,
            'danhSachCotDb' => $danhSachCotDb,
            'giangVien' => $giangVien,
            'danhSachSinhVienAll' => $danhSachSinhVienAll,
            'danhSachGiangVienAll' => $danhSachGiangVienAll,
            'danhSachBaiThiAll' => $danhSachBaiThiAll,
            'danhSachMon' => $danhSachMon,
            'modalBaiThi' => 'modal-bai-thi',
            'dataType' => 'lop_hoc_phan_giang_vien',
            'modalCapNhat' => 'modal-cap-nhat-lop-hoc-phan-giang-vien',
            'modalSinhVien' => 'modal-sinh-vien',
            'id' => $id,
            'id_giang_vien' => $id,

        ]);
    }

    public function handleCapNhatLopHocPhanGiangVien(Request $request) {
        $id = (int)$request->id_lop_hoc_phan;
        $lop_hoc_phan= LopHocPhan::find($id);
        if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_lop_hoc_phan) || $request->ma_lop_hoc_phan !== $lop_hoc_phan->ma_lop_hoc_phan) {
            $existingMaGiangVien = GiangVien::where('ma_lop_hoc_phan', $request->ma_lop_hoc_phan)->first();
            if ($existingMaGiangVien) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã lớp học phần đã tồn tại!'
                ]);
            }
        
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Mã lớp học phần chỉ được chứa chữ cái và số.'
            ]);
        }

        if ($request->ten_lop_hoc_phan !== $lop_hoc_phan->ten_lop_hoc_phan) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_lop_hoc_phan)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên lớp học phần không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }

        if ($lop_hoc_phan) {
            $lop_hoc_phan->ma_lop_hoc_phan= $request->ma_lop_hoc_phan;
            $lop_hoc_phan->ten_lop_hoc_phan= $request->ten_lop_hoc_phan;
            $lop_hoc_phan->ma_mon_hoc= $request->ma_mon_hoc;
            $lop_hoc_phan->thoi_gian_bat_dau= $request->thoi_gian_bat_dau;
            $lop_hoc_phan->thoi_gian_ket_thuc= $request->thoi_gian_ket_thuc;
            $id_giang_vien = $request->id_giang_vien;
            $lop_hoc_phan->save();

            return response()->json([
                'success'   => true,
                'redirect'   => route('giang-vien.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan-giang-vien', ['id' => $id_giang_vien])
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }

    public function handleCapNhatDanhSachSinhVienLopHocPhanGiangVien(Request $request)
    {
        $lopHocPhan = LopHocPhan::find($request->id);
        $lopHocPhan->danh_sach_sinh_vien = $request->danh_sach_sinh_vien;
        $id_giang_vien = $request->id_giang_vien;
        $lopHocPhan->save();
        return response()->json([
            'success'   => true,
            'redirect'   => route('giang-vien.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan-giang-vien', ['id' => $id_giang_vien])
        ]);
    }

    public function handleCapNhatDanhSachBaiThiLopHocPhanGiangVien(Request $request)
    {
        $lopHocPhan = LopHocPhan::find($request->id);
        $lopHocPhan->danh_sach_bai_thi = $request->danh_sach_bai_thi;
        $id_giang_vien = $request->id_giang_vien;
        $lopHocPhan->save();
        return response()->json([
            'success'   => true,
            'redirect'   => route('giang-vien.quan-ly.lop-hoc-phan.quan-ly-lop-hoc-phan-giang-vien', ['id' => $id_giang_vien])
        ]);
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/lop_hoc_phan_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'lop_hoc_phan_template.xlsx');
    }

}
