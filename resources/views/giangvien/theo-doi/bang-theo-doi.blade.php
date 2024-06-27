@extends('layouts.master')

@section('title', 'Bảng Theo Dõi')

@section('content')
<div class="p-6">
    
    <h1 class="text-2xl font-bold mb-4"><button onclick="window.history.back()" class=" text-gray-700 rounded mr-2">
        &lt;
    </button>Bảng Theo Dõi</h1>
    <h2 class="text-lg font-semibold mb-2">Tên bài thi: {{ $tenBaiThi }}</h2>
    <h2 class="text-lg font-semibold mb-2">Lần thi: {{ $lanThi }}</h2>

    <!-- Thanh tìm kiếm -->
    <div class="flex justify-end mb-4">
        <input type="text" id="search" class="w-1/6 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-400" placeholder="Tìm kiếm...">
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Mã Sinh Viên</th>
                    <th class="px-4 py-2">Tên Sinh Viên</th>
                    <th class="px-4 py-2">Trạng Thái</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody id="student-table-body">
                @foreach ($result as $item)
                @php
                    $bgColor = '';
                    if ($item['state'] === 'Chưa làm') {
                        $bgColor = 'bg-white border-gray-300';
                    } elseif ($item['state'] === 'Đang làm') {
                        $bgColor = 'bg-blue-200';
                    } elseif ($item['state'] === 'Đã nộp') {
                        $bgColor = 'bg-green-200';
                    }
                @endphp
                <tr class="{{ $bgColor }}">
                    <td class="px-4 py-2 border">{{ $item['ma_sinh_vien'] }}</td>
                    <td class="px-4 py-2 border">{{ $item['ten_sinh_vien'] }}</td>
                    <td class="px-4 py-2 border">{{ $item['state'] }}</td>
                    <td class="px-4 py-2 border">
                        @if ($item['coTheCapNhat'])
                            <a href="{{ route('giang-vien.quan-ly.theo-doi.cap-nhat-quyen', [
                                'id' => $id,
                                'maLopHocPhan' => $maLopHocPhan,
                                'maBaiThi' => $maBaiThi,
                                'maSinhVien' => $item['ma_sinh_vien'],
                                'lanThi' => $lanThi,
                                'backUrl' => url()->current() 
                            ]) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none" id="capNhatBtn-{{ $item['ma_sinh_vien'] }}">
                                Cập Nhật Quyền
                            </a>
                        @else
                            @if (now()->gt($thoiGianKetThuc->addMinutes(5)))
                                <span class="px-4 py-2 bg-gray-300 text-gray-600 rounded-md cursor-not-allowed">
                                    Quá thời gian cập nhật
                                </span>
                            @else
                                <span class="px-4 py-2 bg-gray-300 text-gray-600 rounded-md cursor-not-allowed">
                                    Không thể cập nhật
                                </span>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div class="mt-4">
        {{ $sinhVienInfo->links() }}
    </div>
</div>

<!-- Script để thực hiện tìm kiếm -->
<script>
document.getElementById('search').addEventListener('input', function() {
    var searchValue = this.value.toLowerCase();
    var rows = document.querySelectorAll('#student-table-body tr');

    rows.forEach(function(row) {
        var maSinhVien = row.cells[0].textContent.toLowerCase();
        var tenSinhVien = row.cells[1].textContent.toLowerCase();
        
        if (maSinhVien.includes(searchValue) || tenSinhVien.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Script để xử lý thời gian cho phép cập nhật quyền
var thoiGianKetThuc = '{{ $thoiGianKetThuc }}'; // Lấy thời gian kết thúc từ controller
var currentTime = new Date();
var endTime = new Date(thoiGianKetThuc);

@if (!now()->lte($thoiGianKetThuc))
    var buttons = document.querySelectorAll('a[id^="capNhatBtn"]');
    buttons.forEach(function(button) {
        button.classList.add('cursor-not-allowed', 'opacity-50');
    });
@endif
</script>
@endsection
