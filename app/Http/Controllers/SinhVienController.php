<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\MonHoc;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
class SinhVienController extends Controller
{
    public function index(){
        $danhSachSinhVien = SinhVien::paginate(10);
        $columnNames = Schema::getColumnListing('sinh_vien');
        $danhSachTenCot = ['ID', 'Mã sinh viên', 'Tên sinh viên', 'Số điện thoại', 'Email', 'Ngày sinh', 'Mã khoa','Mã ngành'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        return view('admin.quan-ly.sinh-vien.index', [
            'title' => 'Danh sách sinh viên',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachSinhVien,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-sinh-vien',
            'modalThem' => 'modal-them-sinh-vien',
            'modalXoa' => 'modal-xoa-sinh-vien',
            'dataType' => 'sinh_vien',
        ]);
    }
    public function handleCapNhatSinhVien(Request $request) {
        $id = (int)$request->id_sinh_vien;
        $sinhVien = SinhVien::find($id);
        if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_sinh_vien) || $request->ma_sinh_vien !== $sinhVien->ma_sinh_vien) {
            $existingMaSinhVien = SinhVien::where('ma_sinh_vien', $request->ma_sinh_vien)->first();
            if ($existingMaSinhVien) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã sinh viên đã tồn tại!'
                ]);
            }
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Mã sinh viên chỉ được chứa chữ cái và số.'
            ]);
        }
        if ($request->ten_sinh_vien !== $sinhVien->ten_sinh_vien) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_sinh_vien)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên sinh viên không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }
        if ($request->so_dien_thoai !== $sinhVien->so_dien_thoai || !preg_match('/^\d{10,11}$/', $request->so_dien_thoai)) {
            if (!preg_match('/^\d{10,11}$/', $request->so_dien_thoai)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Số điện thoại phải có từ 10 đến 11 số.'
                ]);
            }
            $existingNumberPhone = SinhVien::where('so_dien_thoai', $request->so_dien_thoai)->first();
            if ($existingNumberPhone) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Số điện thoại đã tồn tại!'
                ]);
            }
        }
        if ($request->email !== $sinhVien->email) {
            $existingEmail = SinhVien::where('email', $request->email)->first();
            if ($existingEmail) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Email đã tồn tại!'
                ]);
            }

            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)|| !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Email không đúng định dạng.'
                ]);
            }
        }
        if ($request->ngay_sinh !== $sinhVien->ngay_sinh) {
            if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $request-> ngay_sinh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Ngày sinh phải có định dạng YYYY-MM-DD.'
                ]);
            }
        }
        if ($sinhVien) {
            $sinhVien->ma_sinh_vien = $request->ma_sinh_vien;
            $sinhVien->ten_sinh_vien = $request->ten_sinh_vien;
            $sinhVien->so_dien_thoai = $request->so_dien_thoai;
            $sinhVien->email = $request->email;
            $sinhVien->ngay_sinh = $request->ngay_sinh;
            $sinhVien->ma_khoa = $request->ma_khoa;
            $sinhVien->ma_nganh = $request->ma_nganh;
            $sinhVien->save();
            $request->session()->flash('success_message', 'Cập nhật sinh viên thành công!');
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật sinh viên thành công!',
                'redirect'   => route('admin.quan-ly.sinh-vien.quan-ly-sinh-vien')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }
    public function handleThemSinhVien(Request $request) {
        $sinhViens = $request->data;
        if($sinhViens){
            foreach ($sinhViens as $sinhVienData) {
                // Kiểm tra cấu trúc của dữ liệu import từ file
                if (!isset($sinhVienData['ma_sinh_vien']) || !isset($sinhVienData['ten_sinh_vien']) || !isset($sinhVienData['so_dien_thoai']) || !isset($sinhVienData['email']) || !isset($sinhVienData['ngay_sinh']) || !isset($sinhVienData['ma_khoa']) || !isset($sinhVienData['ma_nganh'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Dữ liệu không đúng định dạng.'
                    ]);
                }

                // Kiểm tra mã sinh viên chỉ chứa chữ cái và số
                if (!preg_match('/^[a-zA-Z0-9]+$/', $sinhVienData['ma_sinh_vien'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Mã sinh viên chỉ được chứa chữ cái và số.'
                    ]);
                }

                // Kiểm tra ngày sinh có đúng định dạng YYYY-MM-DD
                if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $sinhVienData['ngay_sinh'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Ngày sinh phải có định dạng YYYY-MM-DD.'
                    ]);
                }
                $existingSinhVien = SinhVien::where('ma_sinh_vien', $sinhVienData['ma_sinh_vien'])->first();
    
                // Nếu sinh viên chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingSinhVien) {
                    $newSinhVien = new SinhVien;
                    $newSinhVien->ma_sinh_vien = $sinhVienData['ma_sinh_vien'];
                    $newSinhVien->ten_sinh_vien = $sinhVienData['ten_sinh_vien'];
                    $newSinhVien->so_dien_thoai = $sinhVienData['so_dien_thoai'];
                    $newSinhVien->email = $sinhVienData['email'];
                    $newSinhVien->ngay_sinh = $sinhVienData['ngay_sinh'];
                    $newSinhVien->ma_khoa = $sinhVienData['ma_khoa'];
                    $newSinhVien->ma_nganh = $sinhVienData['ma_nganh'];
                    $newSinhVien->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm sinh viên thành công!',
                'redirect'   => route('admin.quan-ly.sinh-vien.quan-ly-sinh-vien')
            ]);
        }else{
            if (empty($request->ma_sinh_vien) || empty($request->ten_sinh_vien) || empty($request->so_dien_thoai) || empty($request->email) || empty($request->ngay_sinh) || empty($request->ma_khoa)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_sinh_vien)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã sinh viên chỉ được chứa chữ cái và số.'
                ]);
            }
            $existingSinhVien = SinhVien::where('ma_sinh_vien', $request->ma_sinh_vien)->first();
            if ($existingSinhVien) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã sinh viên đã tồn tại!'
                ]);
            }
            if (preg_match('/[^\p{L}\s]/u', $request->ten_sinh_vien)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên sinh viên không được chứa ký tự đặc biệt và số.'
                ]);
            }
            $existingNumberPhone = SinhVien::where('so_dien_thoai', $request->so_dien_thoai)->first();
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
            $existingEmail = SinhVien::where('email', $request->email)->first();
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
            
            
            $sinhVien = new SinhVien;
            if ($sinhVien) {
                $sinhVien->ma_sinh_vien = $request->ma_sinh_vien;
                $sinhVien->ten_sinh_vien = $request->ten_sinh_vien;
                $sinhVien->so_dien_thoai = $request->so_dien_thoai;
                $sinhVien->email = $request->email;
                $sinhVien->ngay_sinh = $request->ngay_sinh;
                $sinhVien->ma_khoa = $request->ma_khoa;
                $sinhVien->ma_nganh = $request->ma_nganh;
                $sinhVien->save();
                $request->session()->flash('success_message', 'Thêm sinh viên thành công!');
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm sinh viên thành công!',
                    'redirect'   => route('admin.quan-ly.sinh-vien.quan-ly-sinh-vien')
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

    public function handleXoaSinhVien(Request $request) {
        $id = (int)$request->id_sinh_vien;
        $sinhVien = SinhVien::find($id);
        
        if (!$sinhVien) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy sinh viên để xóa!'
            ]);
        }
        
        $sinhVien->delete();

        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.sinh-vien.quan-ly-sinh-vien')
        ]);
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/sinh_vien_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'sinh_vien_template.xlsx');
    }
}
