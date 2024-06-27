@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between">
        <div class="mr-5">
            Danh sách lớp học phần
        </div> 
    </div>
@endsection
@section('content')
    <div class="p-2">
        @if ($dataType = 'lop_hoc_phan_giang_vien')
            @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachMon, $danhSachSinhVienAll])    
        @else 
            @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachMon])    
        @endif
    </div>
@endsection
@include('giangvien.lop-hoc-phan.update-modal')
@include('giangvien.lop-hoc-phan.sinh-vien-modal')
@include('giangvien.lop-hoc-phan.bai-thi-modal') 
@section('page-js')
     <script type="text/javascript">
    </script> 
@endsection