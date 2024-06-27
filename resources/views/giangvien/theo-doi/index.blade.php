@extends('layouts.master')

@section('title')
    {{ $title }}
@endsection

@section('page-title')
    <div class="flex items-center justify-between font-bold">
        <div>
            Lớp hiện tại
        </div>
    </div>
@endsection

@section('content')
<div class="p-2">
    <div class="container mt-4">
        <div id="lopHocPhanList">
            @if (isset($message))
                <div class="alert alert-warning">
                    {{ $message }}
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($thongTinLopHocPhan as $lopHocPhan)
                        <a class="col-span-1 mt-4" href="{{ route('giang-vien.quan-ly.theo-doi.theo-doi-bai-thi', ['id' => $id, 'maLop' => $lopHocPhan['ma_lop_hoc_phan']]) }}">   
                            <div class="border border-gray-300 rounded-md shadow bg-white">
                                <div class="text-gray-500 pb-2">
                                    <h2 class="text-lg font-bold border-b p-2 bg-blue-200">{{ $lopHocPhan['ten_lop_hoc_phan'] }}</h2>
                                    <h2 class="mt-2 text-base font-semibold px-2 text-green-400">Bắt đầu: {{ $lopHocPhan['thoi_gian_bat_dau'] }}</h2>
                                    <h2 class="mt-2 text-base font-semibold px-2 text-red-400">Kết thúc: {{ $lopHocPhan['thoi_gian_ket_thuc'] }}</h2>
                                    <p class="mt-2 text-base font-semibold px-2">Đang có: <span class="text-red-400">{{ $lopHocPhan['so_luong_bai_thi'] }}</span></p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
