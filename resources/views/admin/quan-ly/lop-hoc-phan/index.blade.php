@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between">
        <div class="mr-5">
            Danh sách lớp học phần

        </div>
        <div class="font-normal text-sm mr-5 mt-6">
            @include('admin.quan-ly.lop-hoc-phan.components.add')
        </div> 
    </div>
@endsection
@section('content')
    <div class="p-2">
        @if ($dataType = 'lop_hoc_phan')
            @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachMon, $danhSachSinhVienAll])    
        @else 
            @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachMon])    
        @endif
    </div>
@endsection
@include('admin.quan-ly.lop-hoc-phan.create-modal')
@include('admin.quan-ly.lop-hoc-phan.update-modal')
@include('admin.quan-ly.lop-hoc-phan.delete-modal')
@include('admin.quan-ly.lop-hoc-phan.sinh-vien-modal')
@include('admin.quan-ly.lop-hoc-phan.giang-vien-modal')
@include('admin.quan-ly.lop-hoc-phan.bai-thi-modal')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
@section('page-js')
     <script type="text/javascript">
    </script> 
@endsection