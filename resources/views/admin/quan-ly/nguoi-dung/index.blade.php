@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between">
        <div class="mr-5">
            Danh sách người dùng
        </div>
        <div class="font-normal text-sm mr-2 mt-6">
            @include('admin.quan-ly.nguoi-dung.components.add')
        </div> 
    </div>
@endsection

@section('content')
    <div class="p-2">
        @include('components.datatable', [$danhSachCot, $danhSachDuLieu])
        
    </div>
@endsection
@include('admin.quan-ly.nguoi-dung.create-modal')
\@include('admin.quan-ly.nguoi-dung.create-modal-van-lang')
@include('admin.quan-ly.nguoi-dung.update-modal')
@include('admin.quan-ly.nguoi-dung.delete-modal') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
@section('page-js')
     <script type="text/javascript">
    </script> 
@endsection