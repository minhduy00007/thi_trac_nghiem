@extends('sinhvien.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between font-bold">
        <div>
            Dashboard
        </div>
        
    </div>
@endsection
@section('content')
    
    <div class="mt-4">
        <button id="showLopHocPhan" class="border border-blue-500 hover:bg-blue-500 hover:text-white text-blue-500 font-bold py-2 px-4 rounded mr-2">
            Lớp học phần
        </button>
        <button id="showLichThi" class="border border-green-500 hover:bg-green-500 hover:text-white text-green-500 font-bold py-2 px-4 rounded">
            Lịch thi
        </button>
    </div>
    <div class="container mt-4">
        <div id="lopHocPhanList">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($thongTinLopHocPhan as $lopHocPhan)
                    <a class="col-span-1 mt-4" href="{{ route('sinh-vien.quan-ly.bai-thi.quan-ly-bai-thi-sinh-vien', ['id' => $id, 'maLop' => $lopHocPhan['ma_lop_hoc_phan']]) }}">   
                        <div class="border border-gray-300 rounded-md shadow bg-white">
                            <div class="text-gray-500 pb-2">
                                <h2 class="text-lg font-bold border-b p-2 bg-blue-200">{{ $lopHocPhan['ten_lop_hoc_phan'] }}</h2>
                                <h2 class="mt-2 text-base font-semibold px-2 text-green-400">Bắt đầu: {{ $lopHocPhan['thoi_gian_bat_dau'] }}</h2>
                                <h2 class="mt-2 text-base font-semibold px-2 text-red-400">Kết thúc: {{ $lopHocPhan['thoi_gian_ket_thuc'] }}</h2>
                                <p class="mt-2 text-base font-semibold px-2">Số bài thi đã làm: <span class="font-normal">{{ $lopHocPhan['so_luong_bai_thi_da_lam'] }}</span></p>
                                <p class="mt-2 text-base font-semibold px-2">Đang có: <span class="text-red-400">{{ $lopHocPhan['so_luong_bai_thi'] }}</span></p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div id="lichThiCalendar" style="display: none;"></div>
    </div>
@endsection
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var lopHocPhanList = document.getElementById('lopHocPhanList');
        var lichThiCalendar = document.getElementById('lichThiCalendar');
        var showLopHocPhanBtn = document.getElementById('showLopHocPhan');
        var showLichThiBtn = document.getElementById('showLichThi');

        showLopHocPhanBtn.addEventListener('click', function() {
            lopHocPhanList.style.display = 'block';
            lichThiCalendar.style.display = 'none';
        });

        showLichThiBtn.addEventListener('click', function() {
            lopHocPhanList.style.display = 'none';
            lichThiCalendar.style.display = 'block';
            
            renderLichThiCalendar();
        });

        function renderLichThiCalendar() {
            var calendarEl = document.getElementById('lichThiCalendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                contentHeight: 600,
                events: [
                    @foreach($thongTinLopHocPhan as $lopHocPhan)
                        {
                            title: '{{ $lopHocPhan["ten_lop_hoc_phan"] }}',
                            start: '{{ $lopHocPhan["thoi_gian_bat_dau"] }}',
                            end: '{{ $lopHocPhan["thoi_gian_ket_thuc"] }}'
                        },
                    @endforeach
                ]
            });

            calendar.render();
        }

    });
    
</script>