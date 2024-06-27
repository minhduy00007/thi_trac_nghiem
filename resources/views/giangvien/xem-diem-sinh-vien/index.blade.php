@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between">
        <div class="mr-5">
            Xem điểm 
        </div> 
    </div>
@endsection
@section('content')
    <div class="p-2">
        @if ($dataType = 'xem_diem_sinh_vien_giang_vien')
            @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachMon,  $thongTinLanThi])    
        @else 
            @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachMon])    
        @endif
    </div>
@endsection
@section('page-js')
     <script type="text/javascript">
    </script> 
@endsection