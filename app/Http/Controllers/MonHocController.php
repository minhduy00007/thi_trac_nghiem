<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
class MonHocController extends Controller
{
    public function index(){
        $danhSachSinhVien = MonHoc::paginate(10);
        $columnNames = Schema::getColumnListing('mon_hoc');
        $danhSachTenCot = ['ID', 'Mã môn học', 'Tên môn học'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        return view('admin.quan-ly.mon-hoc.index', [
            'title' => 'Danh sách môn học',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachSinhVien,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-mon-hoc',
            'modalThem' => 'modal-them-mon-hoc',
            'modalXoa' => 'modal-xoa-mon-hoc',
            'dataType' => 'mon_hoc',
        ]);
    }
    public function handleCapNhatMonHoc(Request $request) {
        $id = (int)$request->id_mon_hoc;
        $monHoc = MonHoc::find($id);
        if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_mon_hoc) || ($request->ma_mon_hoc !== $monHoc->ma_mon_hoc)) {
            $existingMaMonHoc = MonHoc::where('ma_mon_hoc', $request->ma_mon_hoc)->first();
            if ($existingMaMonHoc) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã môn học đã tồn tại!'
                ]);
            }
        
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Mã môn học chỉ được chứa chữ cái và số.'
            ]);
        }
        if ($request->ten_mon_hoc !== $monHoc->ten_mon_hoc) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_mon_hoc)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên môn học không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }
        if ($monHoc) {
            $monHoc->ma_mon_hoc = $request->ma_mon_hoc;
            $monHoc->ten_mon_hoc = $request->ten_mon_hoc;
            $monHoc->save();
            $request->session()->flash('success_message', 'Cập nhật môn học thành công!');
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Cập nhật môn học thành công!',
                'redirect'   => route('admin.quan-ly.mon-hoc.quan-ly-mon-hoc')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }
    public function handleThemMonHoc(Request $request) {
        $monHocs = $request->data;
        if($monHocs){
            foreach ($monHocs as $monHocData) {
                if (!isset($monHocData['ma_mon_hoc']) || !isset($monHocData['ten_mon_hoc'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Dữ liệu không đúng định dạng.'
                    ]);
                }
    
                // Kiểm tra mã môn học chỉ chứa chữ cái và số
                if (!preg_match('/^[a-zA-Z0-9]+$/', $monHocData['ma_mon_hoc'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Mã môn học chỉ được chứa chữ cái và số.'
                    ]);
                }
                $existingMonHoc = MonHoc::where('ma_mon_hoc', $monHocData['ma_mon_hoc'])->first();
    
                // Nếu  chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingMonHoc) {
                    $newMonHoc = new MonHoc;
                    $newMonHoc->ma_mon_hoc = $monHocData['ma_mon_hoc'];
                    $newMonHoc->ten_mon_hoc = $monHocData['ten_mon_hoc'];
                    $newMonHoc->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm môn học thành công!',
                'redirect'   => route('admin.quan-ly.mon-hoc.quan-ly-mon-hoc')
            ]);
        }else{

            if (empty($request->ma_mon_hoc) || empty($request->ten_mon_hoc) ) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
    
            if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_mon_hoc)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã môn học chỉ được chứa chữ cái và số.'
                ]);
            }
    
            if (preg_match('/[^\p{L}\s]/u', $request->ten_mon_hoc)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên môn học không được chứa ký tự đặc biệt và số.'
                ]);
            }
            $monHoc = new MonHoc;
            if ($monHoc) {
                $monHoc->ma_mon_hoc = $request->ma_mon_hoc;
                $monHoc->ten_mon_hoc = $request->ten_mon_hoc;
                $monHoc->save();
                $request->session()->flash('success_message', 'Thêm môn học thành công!');
    
                return response()->json([
                    'success'   => true,
                    'type'      => 'success',
                    'message'   => 'Thêm môn học thành công!',
                    'redirect'   => route('admin.quan-ly.mon-hoc.quan-ly-mon-hoc')
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

    public function handleXoaMonHoc(Request $request) {
        $id = (int)$request->id_mon_hoc;
        $monHoc = MonHoc::find($id);
        
        if (!$monHoc) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy môn học để xóa!'
            ]);
        }
        
        $monHoc->delete();
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.mon-hoc.quan-ly-mon-hoc')
        ]);
    }

    public function downloadTemplate()
    {
        $file = public_path('templates/mon_hoc_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'mon_hoc_template.xlsx');
    }
}
