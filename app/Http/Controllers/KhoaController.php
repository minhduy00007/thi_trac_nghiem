<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
class KhoaController extends Controller
{
    public function index(){
        $danhSachSinhVien = Khoa::paginate(10);
        $columnNames = Schema::getColumnListing('khoa');
        $danhSachTenCot = ['ID', 'Mã khoa', 'Tên khoa'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        return view('admin.quan-ly.khoa.index', [
            'title' => 'Danh sách khoa',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachSinhVien,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-khoa',
            'modalThem' => 'modal-them-khoa',
            'modalXoa' => 'modal-xoa-khoa',
            'dataType' => 'khoa',
        ]);
    }
    public function handleCapNhatKhoa(Request $request) {
        $id = (int)$request->id_khoa;
        $khoa = Khoa::find($id);
        if ((!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_khoa) || $request->ma_khoa !== $khoa->ma_khoa)) {
            $existingMaKhoa = SinhVien::where('ma_khoa', $request->ma_khoa)->first();
            if ($existingMaKhoa) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã khoa đã tồn tại!'
                ]);
            }
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Mã khoa chỉ được chứa chữ cái và số.'
            ]);
        }
        if ($request->ten_khoa !== $khoa->ten_khoa) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_khoa)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên khoa không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }
        if ($khoa) {
            $khoa->ma_khoa = $request->ma_khoa;
            $khoa->ten_khoa = $request->ten_khoa;
            $khoa->save();
            $request->session()->flash('success_message', 'Cập nhật khoa thành công!');

            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật khoa thành công!',
                'redirect'   => route('admin.quan-ly.khoa.quan-ly-khoa')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }
    public function handleThemKhoa(Request $request) {
        $khoas = $request->data;
        if($khoas){
            foreach ($khoas as $khoaData) {
                if (!isset($khoaData['ma_khoa']) || !isset($khoaData['ten_khoa'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Dữ liệu không đúng định dạng.'
                    ]);
                }
                // Kiểm tra mã khoa chỉ chứa chữ cái và số
                if (!preg_match('/^[a-zA-Z0-9]+$/', $khoaData['ma_khoa'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Mã khoa chỉ được chứa chữ cái và số.'
                    ]);
                }
                $existingKhoa = Khoa::where('ma_khoa', $khoaData['ma_khoa'])->first();
    
                // Nếu  chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingKhoa) {
                    $newKhoa = new Khoa;
                    $newKhoa->ma_khoa = $khoaData['ma_khoa'];
                    $newKhoa->ten_khoa = $khoaData['ten_khoa'];
                    $newKhoa->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm khoa thành công!',
                'redirect'   => route('admin.quan-ly.khoa.quan-ly-khoa')
            ]);
        }else{

            if (empty($request->ma_khoa) || empty($request->ten_khoa)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_khoa)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã khoa chỉ được chứa chữ cái và số.'
                ]);
            }
            if (preg_match('/[^\p{L}\s]/u', $request->ten_khoa)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên khoa không được chứa ký tự đặc biệt và số.'
                ]);
            }
            $khoa = new Khoa;
            if ($khoa) {
                $khoa->ma_khoa = $request->ma_khoa;
                $khoa->ten_khoa = $request->ten_khoa;
                $khoa->save();
                $request->session()->flash('success_message', 'Thêm khoa thành công!');
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm khoa thành công!',
                    'redirect'   => route('admin.quan-ly.khoa.quan-ly-khoa')
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

    public function handleXoaKhoa(Request $request) {
        $id = (int)$request->id_khoa;
        $khoa = Khoa::find($id);

        if (!$khoa) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy môn học để xóa!'
            ]);
        }
        
        $khoa->delete();

        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.khoa.quan-ly-khoa')
        ]);
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/khoa_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'khoa_template.xlsx');
    }
}
