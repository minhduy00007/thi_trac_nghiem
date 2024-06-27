@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <button onclick="window.history.back()" class=" text-gray-700 px-2 py-2 rounded mr-2">
                &lt;
            </button>
            <div class="mr-5">
                Danh sách bài thi
            </div>
        </div>
        <div class="font-normal text-sm mr-5 mt-6">
            @include('giangvien.bai-thi.components.add')
        </div> 
        
    </div>
@endsection

@section('content')
    <div class="p-2">
        @include('components.datatable', [$danhSachCot, $danhSachDuLieu])
    </div>
@endsection
@include('giangvien.bai-thi.create-modal')
@include('giangvien.bai-thi.update-modal')
@include('giangvien.bai-thi.delete-modal') 
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
@section('page-js')
     <script type="text/javascript">
    </script> 
@endsection