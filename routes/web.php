<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KhoaController;
use App\Http\Controllers\NganhController;
use App\Http\Controllers\BaiThiController;
use App\Http\Controllers\MonHocController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\XemDiemController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\ThongBaoController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\HomeAdminController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\LopHocPhanController;
use App\Http\Controllers\XemLichThiController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Http\Controllers\BaiThiSinhVienController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardSinhVienController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/admin', [HomeController::class, 'adminHome'])->name('trang-admin');
Route::get('/', [AuthController::class, 'dangNhapView'])->name('dang-nhap');
Route::post('/', [AuthController::class, 'handleDangNhap'])->name('handle-dang-nhap');
Route::get('/dang-xuat', [AuthController::class, 'handleDangXuat'])->name('handle-dang-xuat');
Route::get('/dang-nhap-van-lang/{userEmail}/{userName}', [AuthController::class, 'handleDangNhapVanLang'])->name('handle-dang-nhap-van-lang');
Route::get('/microsoft-oauth', [MicrosoftAuthController::class, 'microsoftOAuthLogin'])->name('microsoft-login');
Route::get('/microsoft-oauth-callback', [MicrosoftAuthController::class, 'microsoftOAuthCallback'])->name('microsoft-callback');
Route::get('/microsoft-logout', [MicrosoftAuthController::class, 'microsoftLogout'])->name('microsoft-logout');
Route::get('/logout-other-device', [AuthController::class, 'logoutOtherDevice'])->name('logout-other-device');
Route::get('/thong-bao/{id}', [ThongBaoController::class, 'index'])->name('thong-bao');
Route::post('/check-session', [ThongBaoController::class, 'checkSession'])->name('check-session');
Route::group(['prefix' => 'admin', 'as'=>'admin.'], function() {
    Route::group(['prefix' => 'quan-ly', 'as'=>'quan-ly.'], function() {
        Route::group(['prefix' => 'dashboard', 'as'=>'dashboard.'], function() {
            Route::get('/quan-ly-admin-dashboard', [DashboardAdminController::class, 'index'])->name('admin-dashboard');
        });
        Route::group(['prefix' => 'giang-vien', 'as'=>'giang-vien.'], function() {
            Route::get('/quan-ly-giang-vien', [GiangVienController::class, 'index'])->name('quan-ly-giang-vien');
            Route::put('/quan-ly-giang-vien-cap-nhat', [GiangVienController::class, 'handleCapNhatGiangVien'])->name('handle-cap-nhat-giang-vien');
            Route::post('/quan-ly-giang-vien-them', [GiangVienController::class, 'handleThemGiangVien'])->name('handle-them-giang-vien');
            Route::post('/quan-ly-giang-vien-xoa', [GiangVienController::class, 'handleXoaGiangVien'])->name('handle-xoa-giang-vien');
            Route::post('/quan-ly-cac-mon-giang-day', [GiangVienController::class, 'handleCacMonGiangDay'])->name('handle-cac-mon-giang-day');
            Route::get('/download-template-giang-vien', [GiangVienController::class, 'downloadTemplate'])->name('download-template-giang-vien');
        });
        Route::group(['prefix' => 'sinh-vien', 'as'=>'sinh-vien.'], function() {
            Route::get('/quan-ly-sinh-vien', [SinhVienController::class, 'index'])->name('quan-ly-sinh-vien');
            Route::put('/quan-ly-sinh-vien-cap-nhat', [SinhVienController::class, 'handleCapNhatSinhVien'])->name('handle-cap-nhat-sinh-vien');
            Route::post('/quan-ly-sinh-vien-them', [SinhVienController::class, 'handleThemSinhVien'])->name('handle-them-sinh-vien');
            Route::post('/quan-ly-sinh-vien-xoa', [SinhVienController::class, 'handleXoaSinhVien'])->name('handle-xoa-sinh-vien');
            Route::get('/download-template-sinh-vien', [SinhVienController::class, 'downloadTemplate'])->name('download-template-sinh-vien');
        });
        Route::group(['prefix' => 'mon-hoc', 'as'=>'mon-hoc.'], function() {
            Route::get('/quan-ly-mon-hoc', [MonHocController::class, 'index'])->name('quan-ly-mon-hoc');
            Route::put('/quan-ly-mon-hoc-cap-nhat', [MonHocController::class, 'handleCapNhatMonHoc'])->name('handle-cap-nhat-mon-hoc');
            Route::post('/quan-ly-mon-hoc-them', [MonHocController::class, 'handleThemMonHoc'])->name('handle-them-mon-hoc');
            Route::post('/quan-ly-mon-hoc-xoa', [MonHocController::class, 'handleXoaMonHoc'])->name('handle-xoa-mon-hoc');
            Route::get('/download-template-mon-hoc', [MonHocController::class, 'downloadTemplate'])->name('download-template-mon-hoc');
        });
        Route::group(['prefix' => 'nganh', 'as'=>'nganh.'], function() {
            Route::get('/quan-ly-nganh', [NganhController::class, 'index'])->name('quan-ly-nganh');
            Route::put('/quan-ly-nganh-cap-nhat', [NganhController::class, 'handleCapNhatNganh'])->name('handle-cap-nhat-nganh');
            Route::post('/quan-ly-nganh-them', [NganhController::class, 'handleThemNganh'])->name('handle-them-nganh');
            Route::post('/quan-ly-nganh-xoa', [NganhController::class, 'handleXoaNganh'])->name('handle-xoa-nganh');
            Route::get('/download-template-nganh', [NganhController::class, 'downloadTemplate'])->name('download-template-nganh');
        });
        Route::group(['prefix' => 'khoa', 'as'=>'khoa.'], function() {
            Route::get('/quan-ly-khoa', [KhoaController::class, 'index'])->name('quan-ly-khoa');
            Route::put('/quan-ly-khoa-cap-nhat', [KhoaController::class, 'handleCapNhatkhoa'])->name('handle-cap-nhat-khoa');
            Route::post('/quan-ly-khoa-them', [KhoaController::class, 'handleThemkhoa'])->name('handle-them-khoa');
            Route::post('/quan-ly-khoa-xoa', [KhoaController::class, 'handleXoakhoa'])->name('handle-xoa-khoa');
            Route::get('/download-template-khoa', [KhoaController::class, 'downloadTemplate'])->name('download-template-khoa');
        });
        Route::group(['prefix' => 'lop-hoc-phan', 'as'=>'lop-hoc-phan.'], function() {
            Route::get('/quan-ly-lop-hoc-phan', [LopHocPhanController::class, 'index'])->name('quan-ly-lop-hoc-phan');
            Route::put('/quan-ly-lop-hoc-phan-cap-nhat', [LopHocPhanController::class, 'handleCapNhatLopHocPhan'])->name('handle-cap-nhat-lop-hoc-phan');
            Route::put('/quan-ly-lop-hoc-phan-cap-nhat-danh-sach-sinh-vien', [LopHocPhanController::class, 'handleCapNhatDanhSachSinhVienLopHocPhan'])->name('handle-cap-nhat-danh-sach-sinh-vien-lop-hoc-phan');
            Route::put('/quan-ly-lop-hoc-phan-cap-nhat-danh-sach-giang-vien', [LopHocPhanController::class, 'handleCapNhatDanhSachGiangVienLopHocPhan'])->name('handle-cap-nhat-danh-sach-giang-vien-lop-hoc-phan');
            Route::put('/quan-ly-lop-hoc-phan-cap-nhat-danh-sach-bai-thi', [LopHocPhanController::class, 'handleCapNhatDanhSachBaiThiLopHocPhan'])->name('handle-cap-nhat-danh-sach-bai-thi-lop-hoc-phan');
            Route::post('/quan-ly-lop-hoc-phan-them', [LopHocPhanController::class, 'handleThemLopHocPhan'])->name('handle-them-lop-hoc-phan');
            Route::post('/quan-ly-lop-hoc-phan-xoa', [LopHocPhanController::class, 'handleXoaLopHocPhan'])->name('handle-xoa-lop-hoc-phan');
            Route::get('/download-template-lop-hoc-phan', [LopHocPhanController::class, 'downloadTemplate'])->name('download-template-lop-hoc-phan');
        });
        Route::group(['prefix' => 'bai-thi', 'as'=>'bai-thi.'], function() {
            Route::get('/quan-ly-bai-thi', [BaiThiController::class, 'index'])->name('quan-ly-bai-thi');
            Route::get('/quan-ly-bai-thi-cau-hoi/{id}', [BaiThiController::class, 'handleCauHoi'])->name('quan-ly-bai-thi-cau-hoi');
            Route::put('/quan-ly-bai-thi-cap-nhat', [BaiThiController::class, 'handleCapNhatBaiThi'])->name('handle-cap-nhat-bai-thi');
            Route::post('/quan-ly-bai-thi-them', [BaiThiController::class, 'handleThemBaiThi'])->name('handle-them-bai-thi');
            Route::post('/quan-ly-bai-thi-xoa', [BaiThiController::class, 'handleXoaBaiThi'])->name('handle-xoa-bai-thi');
            Route::post('/quan-ly-bai-thi-them-cau-hoi', [BaiThiController::class, 'handleThemCauHoi'])->name('handle-them-bai-thi-cau-hoi');
            Route::get('/download-template', [BaiThiController::class, 'downloadTemplate'])->name('download-template');
            Route::get('/download-template-bai-thi', [BaiThiController::class, 'downloadTemplateBaiThi'])->name('download-template-bai-thi');
            Route::get('/phan-cong-bai-thi/{maLopHocPhan}', [BaiThiController::class, 'indexPhanCong'])->name('phan-cong-bai-thi');
            Route::get('/quan-ly-bai-thi-phan-cong-giang-vien/{maLopHocPhan}/{maBaiThi}/{lanThi}', [BaiThiController::class, 'indexPhanCongGiangVien'])->name('bang-phan-cong-giang-vien');
            Route::post('/quan-ly-bai-thi-them-phan-cong-giang-vien', [BaiThiController::class, 'handleThemGiangVienPhanCong'])->name('handle-them-phan-cong-giang-vien');
            Route::post('/quan-ly-bai-thi-xoa-phan-cong-giang-vien', [BaiThiController::class, 'handleXoaGiangVienPhanCong'])->name('handle-xoa-phan-cong-giang-vien');
        });
        Route::group(['prefix' => 'nguoi-dung', 'as'=>'nguoi-dung.'], function() {
            Route::get('/quan-ly-nguoi-dung', [NguoiDungController::class, 'index'])->name('quan-ly-nguoi-dung');
            Route::put('/quan-ly-nguoi-dung-cap-nhat', [NguoiDungController::class, 'handleCapNhatNguoiDung'])->name('handle-cap-nhat-nguoi-dung');
            Route::post('/quan-ly-nguoi-dung-them', [NguoiDungController::class, 'handleThemNguoiDung'])->name('handle-them-nguoi-dung');
            Route::post('/quan-ly-nguoi-dung-xoa', [NguoiDungController::class, 'handleXoaNguoiDung'])->name('handle-xoa-nguoi-dung');
            Route::post('/quan-ly-nguoi-dung-them-van-lang', [NguoiDungController::class, 'handleThemNguoiDungEmail'])->name('handle-them-nguoi-dung-email');
            Route::get('/download-template-nguoi-dung', [NguoiDungController::class, 'downloadTemplate'])->name('download-template-nguoi-dung');
        });
    });
});

Route::group(['prefix' => 'sinh-vien', 'as'=>'sinh-vien.'], function() {
    Route::group(['prefix' => 'quan-ly', 'as'=>'quan-ly.'], function() {
        Route::group(['prefix' => '', 'as'=>'dashboard.'], function() {
            Route::get('/quan-ly-dashboard/{id}', [DashboardSinhVienController::class, 'index'])->name('quan-ly-dashboard');
        });
        Route::group(['prefix' => '', 'as'=>'bai-thi.'], function() {
            Route::get('/quan-ly-bai-thi-sinh-vien/{id}/{maLop}', [BaiThiSinhVienController::class, 'index'])->name('quan-ly-bai-thi-sinh-vien');
            Route::get('/quan-ly-lam-bai-thi-sinh-vien/{id}/{maLopHocPhan}/{maBaiThi}/{lanThi}', [BaiThiSinhVienController::class, 'lamBaiThi'])->name('quan-ly-lam-bai-thi-sinh-vien');
            Route::get('/quan-ly-lam-bai-thi-trac-nghiem-sinh-vien/{id}/{maLopHocPhan}/{maBaiThi}/{lanThi}', [BaiThiSinhVienController::class, 'lamBaiThiTracNghiem'])->name('quan-ly-lam-bai-thi-trac-nghiem-sinh-vien');
        });
        Route::group(['prefix' => '', 'as'=>'xem-diem.'], function() {
            Route::get('/xem-diem-sinh-vien/{id}', [XemDiemController::class, 'index'])->name('xem-diem-sinh-vien');
            Route::post('/quan-ly-sinh-vien-them-diem', [XemDiemController::class, 'handleThemDiemSinhVien'])->name('handle-them-diem-sinh-vien');
        });
    });
});

Route::group(['prefix' => 'giang-vien', 'as'=>'giang-vien.'], function() {
    Route::group(['prefix' => 'quan-ly', 'as'=>'quan-ly.'], function() {
        Route::group(['prefix' => 'lop-hoc-phan', 'as'=>'lop-hoc-phan.'], function() {
            Route::get('/quan-ly-lop-hoc-phan-giang-vien/{id}', [LopHocPhanController::class, 'indexLopHocPhanGiangVien'])->name('quan-ly-lop-hoc-phan-giang-vien');
            Route::put('/quan-ly-lop-hoc-phan-giang-vien-cap-nhat', [LopHocPhanController::class, 'handleCapNhatLopHocPhanGiangVien'])->name('handle-cap-nhat-lop-hoc-phan-giang-vien');
            Route::put('/quan-ly-lop-hoc-phan-giang-vien-cap-nhat-danh-sach-sinh-vien', [LopHocPhanController::class, 'handleCapNhatDanhSachSinhVienLopHocPhanGiangVien'])->name('handle-cap-nhat-danh-sach-sinh-vien-lop-hoc-phan-giang-vien');
            Route::put('/quan-ly-lop-hoc-phan-giang-vien-cap-nhat-danh-sach-bai-thi', [LopHocPhanController::class, 'handleCapNhatDanhSachBaiThiLopHocPhanGiangVien'])->name('handle-cap-nhat-danh-sach-bai-thi-lop-hoc-phan-giang-vien');
        });
        Route::group(['prefix' => 'bai-thi', 'as'=>'bai-thi.'], function() {
            Route::get('/quan-ly-bai-thi-giang-vien/{id}/{maLopHocPhan}', [BaiThiController::class, 'indexBaiThiGiangVien'])->name('quan-ly-bai-thi-giang-vien');
            Route::post('/quan-ly-bai-thi-them', [BaiThiController::class, 'handleThemBaiThiGiangVien'])->name('handle-them-bai-thi');
            Route::put('/quan-ly-bai-thi-cap-nhat', [BaiThiController::class, 'handleCapNhatBaiThiGiangVien'])->name('handle-cap-nhat-bai-thi');
            Route::post('/quan-ly-bai-thi-xoa', [BaiThiController::class, 'handleXoaBaiThiGiangVien'])->name('handle-xoa-bai-thi');
            Route::get('/quan-ly-bai-thi-cau-hoi/{id}/{id_giang_vien}', [BaiThiController::class, 'handleCauHoiGiangVien'])->name('quan-ly-bai-thi-cau-hoi');
            Route::post('/quan-ly-bai-thi-them-cau-hoi', [BaiThiController::class, 'handleThemCauHoiGiangVien'])->name('handle-them-bai-thi-cau-hoi');
            Route::get('/download-template', [BaiThiController::class, 'downloadTemplate'])->name('download-template');
            Route::get('/download-template-bai-thi-giang-vien', [BaiThiController::class, 'downloadTemplateBaiThiGiangVien'])->name('download-template-bai-thi-giang-vien');
        });
        Route::group(['prefix' => '', 'as'=>'xem-diem.'], function() {
            Route::get('/xem-diem-sinh-vien-giang-vien/{id}', [XemDiemController::class, 'indexXemDiemSinhVienGiangVien'])->name('xem-diem-sinh-vien-giang-vien');
            Route::get('/bang-diem-sinh-vien-giang-vien/{id}/{ma_lop_hoc_phan}/{ma_bai_thi}/{lan_thi}', [XemDiemController::class, 'bangDiemSinhVienGiangVien'])->name('bang-diem-sinh-vien-giang-vien');
            Route::post('/public-diem', [XemDiemController::class, 'publicDiem'])->name('publicDiem');
            Route::post('/unpublic-diem', [XemDiemController::class, 'unpublicDiem'])->name('unpublicDiem');
            Route::get('/chi-tiet-bai-thi', [GiangVienController::class, 'chiTietBaiThi'])->name('chi-tiet-bai-thi');
        });
        Route::group(['prefix' => '', 'as'=>'xem-lich-thi.'], function() {
            Route::get('/xem-lich-thi/{id}', [XemLichThiController::class, 'xemLichThi'])->name('xem-lich-thi-giang-vien');
        });
        Route::group(['prefix' => '', 'as'=>'theo-doi.'], function() {
            Route::get('/lop-hoc-phan/{id}', [GiangVienController::class, 'indexGiamSat'])->name('lop-hoc-phan');
            Route::get('/bai-thi/{id}/{maLop}', [GiangVienController::class, 'indexBaiThiTheoDoi'])->name('theo-doi-bai-thi');
            Route::get('/bang-theo-doi/{id}/{maLopHocPhan}/{maBaiThi}/{lanThi}', [GiangVienController::class, 'bangTheoDoi'])->name('bang-theo-doi');
            Route::get('/cap-nhat-quyen/{id}/{maLopHocPhan}/{maBaiThi}/{maSinhVien}/{lanThi}', [GiangVienController::class, 'capNhatQuyen'])->name('cap-nhat-quyen');
        });
    });
});
