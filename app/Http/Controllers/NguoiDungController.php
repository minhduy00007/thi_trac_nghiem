<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\MonHoc;
use App\Models\SinhVien;
use App\Models\GiangVien;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class NguoiDungController extends Controller
{
    public function index(){
        $danhSachNguoiDung = NguoiDung::paginate(10);
        $columnNames = Schema::getColumnListing('nguoi_dung');
        $danhSachTenCot = ['ID', 'Họ tên', 'Email', 'Role'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();

        // Kiểm tra và thêm sinh viên và giảng viên
        $sinhVienEmails = SinhVien::pluck('email')->toArray();
        $giangVienEmails = GiangVien::pluck('email')->toArray();

        return view('admin.quan-ly.nguoi-dung.index', [
            'title' => 'Danh sách người dùng',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachNguoiDung,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-nguoi-dung',
            'modalThem' => 'modal-them-nguoi-dung',
            'modalThemEmail' => 'modal-them-nguoi-dung-email',
            'modalXoa' => 'modal-xoa-nguoi-dung',
            'dataType' => 'nguoi_dung',
        ]);
    }
    public function handleCapNhatNguoiDung(Request $request) {
        $id = (int)$request->id_nguoi_dung;
        $nguoiDung = NguoiDung::find($id);
        if ($request->ho_ten !== $nguoiDung->ho_ten) {
            if (preg_match('/[^\p{L}\s]/u', $request->ho_ten)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên người dùng không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }
        if ($request->email !== $nguoiDung->email) {
            $existingEmail = NguoiDung::where('email', $request->email)->first();
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
        
        if ($request->email !== $nguoiDung->email) {
            if (!preg_match('/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{6,}$/', $request->mat_khau)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mật khẩu phải chứa ít nhất 6 ký tự và chỉ được chứa chữ cái hoặc số.'
                ]);
            }
        }
        if ($nguoiDung) {
            $nguoiDung->ho_ten = $request->ho_ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->role = $request->role;
            

            if ($request->role === 'Sinh viên') {
                // Kiểm tra cấu trúc của ho_ten
                if (preg_match('/^(\d+CT\d+) - (.+) - (.+)$/', $request->ho_ten, $matches)) {
                    // Tách mã sinh viên và tên sinh viên từ ho_ten của người dùng
                    $maSinhVien = $matches[1];
                    $tenSinhVien = $matches[2];
        
                    $existingSinhVien = SinhVien::where('email', $request->email)->first();
                    if (!$existingSinhVien) {
                        SinhVien::create([
                            'email' => $request->email,
                            'ma_sinh_vien' => $maSinhVien,
                            'ten_sinh_vien' => $tenSinhVien,
                        ]);
                    }
                } else {
                    // Tạo mã ngẫu nhiên nếu ho_ten không đúng cấu trúc
                    $maSinhVien = "Guest" . rand(1, 100);
                    $existingSinhVien = SinhVien::where('email', $request->email)->first();
                    if (!$existingSinhVien) {
                        SinhVien::create([
                            'email' => $request->email,
                            'ma_sinh_vien' => $maSinhVien,
                            'ten_sinh_vien' => $request->ho_ten,
                        ]);
                    }
                }
                // Kiểm tra và xóa giảng viên trùng email nếu có
                $existingGiangVien = GiangVien::where('email', $request->email)->first();
                if ($existingGiangVien) {
                    $existingGiangVien->delete();
                }
            }
    
            if ($request->role === 'Giảng viên') {
                // Kiểm tra cấu trúc của ho_ten
                if (preg_match('/^(\d+CT\d+) - (.+) - (.+)$/', $request->ho_ten, $matches)) {
                    // Tách mã Giảng viên và tên Giảng viên từ ho_ten của người dùng
                    $maGiangVien = $matches[1];
                    $tenGiangVien = $matches[2];
        
                    $existingGiangVien = GiangVien::where('email', $request->email)->first();
                    if (!$existingGiangVien) {
                        GiangVien::create([
                            'email' => $request->email,
                            'ma_giang_vien' => $maGiangVien,
                            'ten_giang_vien' => $tenGiangVien,
                        ]);
                    }else {
                        // Tạo mã ngẫu nhiên nếu ho_ten không đúng cấu trúc
                        $maGiangVien = "Guest" . rand(1, 100);
                        $existingGiangVien = GiangVien::where('email', $request->email)->first();
                        if (!$existingGiangVien) {
                            GiangVien::create([
                                'email' => $request->email,
                                'ma_giang_vien' => $maGiangVien,
                                'ten_giang_vien' => $request->ho_ten,
                            ]);
                        }   
                    }
                } 
                // Kiểm tra và xóa sinh viên trùng email nếu có
                $existingSinhVien = SinhVien::where('email', $request->email)->first();
                if ($existingSinhVien) {
                    $existingSinhVien->delete();
                }
            }
            
            $nguoiDung->save();

            $request->session()->flash('success_message', 'Cập nhật người thành công!');
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật người dùng thành công!',
                'redirect'   => route('admin.quan-ly.nguoi-dung.quan-ly-nguoi-dung')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }

    public function handleThemNguoiDung(Request $request) {
        $nguoiDungs = $request->data;
        if ($nguoiDungs) {
            foreach ($nguoiDungs as $nguoiDungData) {
                $existingNguoiDung = NguoiDung::where('email', $nguoiDungData['email'])->first();
    
                // Nếu giang viên chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingNguoiDung) {
                    $newNguoiDung = new NguoiDung;
                    $newNguoiDung->ho_ten = $nguoiDungData['ho_ten'];
                    $newNguoiDung->email = $nguoiDungData['email'];
                    $newNguoiDung->role = $nguoiDungData['role'];
                    $newNguoiDung->save();
                    if ($request->role === 'Sinh viên') {
                        // Kiểm tra cấu trúc của ho_ten
                        if (preg_match('/^(\d+CT\d+) - (.+) - (.+)$/', $request->ho_ten, $matches)) {
                            // Tách mã sinh viên và tên sinh viên từ ho_ten của người dùng
                            $maSinhVien = $matches[1];
                            $tenSinhVien = $matches[2];
                
                            $existingSinhVien = SinhVien::where('email', $request->email)->first();
                            if (!$existingSinhVien) {
                                SinhVien::create([
                                    'email' => $request->email,
                                    'ma_sinh_vien' => $maSinhVien,
                                    'ten_sinh_vien' => $tenSinhVien,
                                ]);
                            }
                        } else {
                            // Tạo mã ngẫu nhiên nếu ho_ten không đúng cấu trúc
                            $maSinhVien = "Guest" . rand(1, 100);
                            $existingSinhVien = SinhVien::where('email', $request->email)->first();
                            if (!$existingSinhVien) {
                                SinhVien::create([
                                    'email' => $request->email,
                                    'ma_sinh_vien' => $maSinhVien,
                                    'ten_sinh_vien' => $request->ho_ten,
                                ]);
                            }
                        }
                    }
                }
                if ($request->role === 'Giảng viên') {
                    // Kiểm tra cấu trúc của ho_ten
                    if (preg_match('/^(\d+CT\d+) - (.+) - (.+)$/', $request->ho_ten, $matches)) {
                        // Tách mã Giảng viên và tên Giảng viên từ ho_ten của người dùng
                        $maGiangVien = $matches[1];
                        $tenGiangVien = $matches[2];
            
                        $existingGiangVien = GiangVien::where('email', $request->email)->first();
                        if (!$existingGiangVien) {
                            GiangVien::create([
                                'email' => $request->email,
                                'ma_giang_vien' => $maGiangVien,
                                'ten_giang_vien' => $tenGiangVien,
                            ]);
                        }else {
                            // Tạo mã ngẫu nhiên nếu ho_ten không đúng cấu trúc
                            $maGiangVien = "Guest" . rand(1, 100);
                            $existingGiangVien = GiangVien::where('email', $request->email)->first();
                            if (!$existingGiangVien) {
                                GiangVien::create([
                                    'email' => $request->email,
                                    'ma_giang_vien' => $maGiangVien,
                                    'ten_giang_vien' => $request->ho_ten,
                                ]);
                            }   
                        }
                    } 
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm người dùng thành công!',
                'redirect'   => route('admin.quan-ly.nguoi-dung.quan-ly-nguoi-dung')
            ]);
        }else{

            if (empty($request->ho_ten) || empty($request->email) || empty($request->mat_khau) ) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
    
            if (preg_match('/[^\p{L}\s]/u', $request->ho_ten)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên người dùng không được chứa ký tự đặc biệt và số.'
                ]);
            }
    
            $existingEmail = NguoiDung::where('email', $request->email)->first();
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
    
            if (!preg_match('/^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{6,}$/', $request->mat_khau)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mật khẩu phải chứa ít nhất 6 ký tự và chỉ được chứa chữ cái hoặc số.'
                ]);
            }
            $nguoiDung = new NguoiDung;
            if ($nguoiDung) {
                $nguoiDung->ho_ten = $request->ho_ten;
                $nguoiDung->email = $request->email;
                $nguoiDung->mat_khau = Hash::make($request->mat_khau);
                $nguoiDung->role = $request->role;
                $nguoiDung->save();
                $request->session()->flash('success_message', 'Thêm người dùng thành công!');
    
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm người dùng thành công!',
                    'redirect'   => route('admin.quan-ly.nguoi-dung.quan-ly-nguoi-dung')
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

    public function handleXoaNguoiDung(Request $request) {
        $id = (int)$request->id_nguoi_dung;
        $nguoiDung = NguoiDung::find($id);
        
        if (!$nguoiDung) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy người dùng để xóa!'
            ]);
        }
        
        $nguoiDung->delete();
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.nguoi-dung.quan-ly-nguoi-dung')
        ]);
    }

    // Dành cho email đã được cấp
    public function handleThemNguoiDungEmail(Request $request) {
        if (empty($request->ho_ten) || empty($request->email) ) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Vui lòng điền đầy đủ thông tin!'
            ]);
        }

        if (preg_match('/[^\p{L}\s]/u', $request->ho_ten)) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Tên người dùng không được chứa ký tự đặc biệt và số.'
            ]);
        }

        $existingEmail = NguoiDung::where('email', $request->email)->first();
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

        $nguoiDung = new NguoiDung;
        if ($nguoiDung) {
            $nguoiDung->ho_ten = $request->ho_ten;
            $nguoiDung->email = $request->email;
            $nguoiDung->role = $request->role;
            $nguoiDung->save();
            $request->session()->flash('success_message', 'Thêm người dùng thành công!');

            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm người dùng thành công!',
                'redirect'   => route('admin.quan-ly.nguoi-dung.quan-ly-nguoi-dung')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình thêm!'
            ]);
        }
       
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/nguoi_dung_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'nguoi_dung_template.xlsx');
    }
}
