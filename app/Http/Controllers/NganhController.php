<?php

namespace App\Http\Controllers;

use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
class NganhController extends Controller
{
    public function index(){
        $danhSachSinhVien = Nganh::paginate(10);
        $columnNames = Schema::getColumnListing('nganh');
        $danhSachTenCot = ['ID', 'Mã ngành', 'Tên ngành', 'Mã khoa'];
        $danhSachCot = [];
        $danhSachCotDb = [];
        for ($i = 0; $i < sizeof($danhSachTenCot); $i++) {
            $danhSachCot[] = $danhSachTenCot[$i];
            $danhSachCotDb[] = $columnNames[$i];
        }
        $danhSachKhoa = Khoa::all();
        $danhSachMon = MonHoc::all();
        $danhSachNganh = Nganh::all();
        return view('admin.quan-ly.nganh.index', [
            'title' => 'Danh sách ngành',
            'danhSachCot' => $danhSachCot,
            'danhSachDuLieu' => $danhSachSinhVien,
            'danhSachCotDb' => $danhSachCotDb,
            'danhSachMon' => $danhSachMon,
            'danhSachKhoa' => $danhSachKhoa,
            'danhSachNganh' => $danhSachNganh,
            'modalCapNhat' => 'modal-cap-nhat-nganh',
            'modalThem' => 'modal-them-nganh',
            'modalXoa' => 'modal-xoa-nganh',
            'dataType' => 'nganh',
        ]);
    }
    public function handleCapNhatNganh(Request $request) {
        $id = (int)$request->id_nganh;
        $nganh = Nganh::find($id);
        if ((!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_nganh) || $request->ma_nganh !== $nganh->ma_nganh)) {
            $existingMaNganh = SinhVien::where('ma_nganh', $request->ma_nganh)->first();
            if ($existingMaNganh) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã ngành đã tồn tại!'
                ]);
            }
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Mã ngành chỉ được chứa chữ cái và số.'
            ]);
        }
        if ($request->ten_nganh !== $nganh->ten_nganh) {
            if (preg_match('/[^\p{L}\s]/u', $request->ten_nganh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên ngành không được chứa ký tự đặc biệt và số.'
                ]);
            }
        }
        if ($nganh) {
            $nganh->ma_nganh = $request->ma_nganh;
            $nganh->ten_nganh = $request->ten_nganh;
            $nganh->ma_khoa = $request->ma_khoa;
            $nganh->save();
            $request->session()->flash('success_message', 'Cập nhật ngành thành công!');

            return response()->json([
                'success'   => true,
                'redirect'   => route('admin.quan-ly.nganh.quan-ly-nganh')
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Có lỗi xảy ra trong quá trình cập nhật!'
            ]);
        }
    }
    public function handleThemNganh(Request $request) {
        $nganhs = $request->data;
        if($nganhs){
            foreach ($nganhs as $nganhData) {
                if (!isset($nganhData['ma_nganh']) || !isset($nganhData['ten_nganh']) || !isset($nganhData['ma_khoa'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Dữ liệu không đúng định dạng.'
                    ]);
                }
    
                // Kiểm tra mã ngành chỉ chứa chữ cái và số
                if (!preg_match('/^[a-zA-Z0-9]+$/', $nganhData['ma_nganh'])) {
                    return response()->json([
                        'success'   => false,
                        'type'      => 'error',
                        'message'   => 'Mã ngành chỉ được chứa chữ cái và số.'
                    ]);
                }
                $existingNganh = Nganh::where('ma_nganh', $nganhData['ma_nganh'])->first();
    
                // Nếu  chưa tồn tại, thêm mới vào cơ sở dữ liệu
                if (!$existingNganh) {
                    $newNganh = new Nganh;
                    $newNganh->ma_nganh = $nganhData['ma_nganh'];
                    $newNganh->ten_nganh = $nganhData['ten_nganh'];
                    $newNganh->ma_khoa = $nganhData['ma_khoa'];
                    $newNganh->save();
                }
            }
    
            return response()->json([
                'success'   => true,
                'type'      => 'success',
                'message'   => 'Thêm ngành thành công!',
                'redirect'   => route('admin.quan-ly.nganh.quan-ly-nganh')
            ]);
        }else{

            if (empty($request->ma_nganh) || empty($request->ten_nganh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Vui lòng điền đầy đủ thông tin!'
                ]);
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $request->ma_nganh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Mã ngành chỉ được chứa chữ cái và số.'
                ]);
            }
            if (preg_match('/[^\p{L}\s]/u', $request->ten_nganh)) {
                return response()->json([
                    'success'   => false,
                    'type'      => 'error',
                    'message'   => 'Tên ngành không được chứa ký tự đặc biệt và số.'
                ]);
            }
            $nganh = new nganh;
            if ($nganh) {
                $nganh->ma_nganh = $request->ma_nganh;
                $nganh->ten_nganh = $request->ten_nganh;
                $nganh->ma_khoa = $request->ma_khoa;
                $nganh->save();
                $request->session()->flash('success_message', 'Thêm ngành thành công!');
                return response()->json([
                    'success'   => true,
                    'redirect'   => route('admin.quan-ly.nganh.quan-ly-nganh')
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

    public function handleXoaNganh(Request $request) {
        $id = (int)$request->id_nganh;
        $nganh = Nganh::find($id);
        
        // Nếu không tìm thấy giảng viên, trả về thông báo lỗi
        if (!$nganh) {
            return response()->json([
                'success'   => false,
                'type'      => 'error',
                'message'   => 'Không tìm thấy môn học để xóa!'
            ]);
        }
        
        // Nếu tìm thấy giảng viên, tiến hành xóa
        $nganh->delete();

        // Trả về thông báo thành công và redirect về trang danh sách giảng viên
        return response()->json([
            'success'   => true,
            'redirect'   => route('admin.quan-ly.nganh.quan-ly-nganh')
        ]);
    }
    public function downloadTemplate()
    {
        $file = public_path('templates/nganh_template.xlsx'); // Đường dẫn đến tệp mẫu Excel

        return response()->download($file, 'nganh_template.xlsx');
    }
}
