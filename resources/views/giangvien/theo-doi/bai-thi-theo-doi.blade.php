@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center">
        <button onclick="window.history.back()" class=" text-gray-700 px-2 py-2 rounded mr-2">
            &lt;
        </button>
        <div class="mr-5">
            Bài thi
        </div>
    </div>
@endsection
@section('content')
    <div class="p-2">
        <div class="container mt-4">
            @if (empty($thongTinBaiThi))
                <div class="alert alert-warning">
                    Giảng viên chưa có bài thi nào được phân công.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($thongTinBaiThi as $baiThi)
                        @php
                            $currentDateTime = now();
                            $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $baiThi['thoi_gian_bat_dau']);
                            $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $baiThi['thoi_gian_ket_thuc']);
                            $isDisabled = $currentDateTime->lt($startDateTime) || $currentDateTime->gt($endDateTime);
                        @endphp

                        <a class="col-span-1 mt-4" href="{{ route('giang-vien.quan-ly.theo-doi.bang-theo-doi', ['id' => $id, 'maLopHocPhan' => $maLopHocPhan,'maBaiThi' => $baiThi['ma_bai_thi'], 'lanThi' => $baiThi['lan_thi']]) }}">
                            <div class="border border-gray-300 rounded-md shadow bg-white">
                                <div class="text-gray-500 pb-2">
                                    <h2 class="text-lg font-bold border-b p-2 bg-blue-200">{{ $baiThi['ten_bai_thi'] }} - Lần {{ $baiThi['lan_thi'] }}</h2>
                                    <p class="mt-2 text-base font-semibold px-2">Bắt đầu: <span class="text-green-400">{{ \Carbon\Carbon::parse($baiThi['thoi_gian_bat_dau'])->format('l, d/m/Y, H:i') }}</span></p>
                                    <p class="mt-2 text-base font-semibold px-2">Kết thúc: <span class="text-red-400">{{ \Carbon\Carbon::parse($baiThi['thoi_gian_ket_thuc'])->format('l, d/m/Y, H:i') }}</span></p>
                                    <p class="mt-2 text-base font-semibold px-2">Số câu: {{ $baiThi['tongSoCauHoi'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
