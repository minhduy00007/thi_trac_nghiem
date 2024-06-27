@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between">
        <div class="mr-5">
            Danh sách sinh viên
        </div>
        <div class="font-normal text-sm mr-5 mt-6">
            @include('admin.quan-ly.sinh-vien.components.add')
        </div> 
    </div>
@endsection
@section('content')
    <div class="p-2">
        @include('components.datatable', [$danhSachCot, $danhSachDuLieu, $danhSachNganh])
    </div>
@endsection
@include('admin.quan-ly.sinh-vien.create-modal')
@include('admin.quan-ly.sinh-vien.update-modal')
@include('admin.quan-ly.sinh-vien.delete-modal')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
@section('page-js')
     <script type="text/javascript">
    </script> 
@endsection