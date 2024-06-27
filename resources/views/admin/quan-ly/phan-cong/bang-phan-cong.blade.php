@extends('layouts.master')

@section('title')
    Phân công giảng viên
@endsection

@section('page-title')
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <button onclick="window.history.back()" class="text-gray-700 px-2 py-2 rounded mr-2">
                &lt;
            </button>
            <div class="text-lg font-semibold">
                Phân công giảng viên
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="flex justify-between items-center mb-2">
    <div class="pl-2 mt-4">
        <p class="text-lg"><strong>Lớp Học Phần:</strong> {{ $maLopHocPhan }} - {{ $tenLopHocPhan }}</p> 
        <p class="text-lg"><strong>Bài Thi:</strong> {{ $maBaiThi }} - {{ $tenBaiThi }}</p>
        <p class="text-lg"><strong>Lần Thi:</strong> {{ $lanThi }}</p>
    </div>
    <input type="text" id="searchInput" class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-400" placeholder="Tìm kiếm...">
</div>
<div class="p-2">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @if($danhSachgiangVien->isEmpty())
            <p class="text-center text-gray-600 dark:text-gray-400">Không có giảng viên nào.</p>
        @else
            <table id="dataTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(0)">Mã Giảng Viên <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(1)">Tên Giảng Viên <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3">Phân công</th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhSachgiangVien as $giangViens)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4">{{ $giangViens->ma_giang_vien }}</td>
                            <td class="px-6 py-4">{{ $giangViens->ten_giang_vien }}</td>
                            <td class="px-6 py-4">
                                @if(!empty($giangViens->phan_cong))
                                    @foreach($giangViens->phan_cong as $assignment)
                                        <p>{{ $assignment['ma_lop_hoc_phan'] }} - {{ $assignment['ma_bai_thi'] }}</p>
                                    @endforeach
                                @else
                                    Chưa có phân công
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2" onclick="addGiangVien('{{ $giangViens->ma_giang_vien }}')">Add</button>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="deleteGiangVien('{{ $giangViens->ma_giang_vien }}')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $danhSachgiangVien->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


@section('page-js')
    <script type="text/javascript">
        const searchInput = document.getElementById('searchInput');
        const dataTable = document.getElementById('dataTable');

        searchInput.addEventListener('input', function() {
            const searchText = searchInput.value.trim().toLowerCase();
            const rows = dataTable.querySelectorAll('tbody tr');

            rows.forEach(row => {
                let rowMatch = false;
                row.querySelectorAll('td').forEach(cell => {
                    if (cell.textContent.trim().toLowerCase().includes(searchText)) {
                        rowMatch = true;
                    }
                });

                row.style.display = rowMatch ? '' : 'none';
            });
        });

        function sortTable(columnIndex) {
            const table = dataTable;
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            const isNumericColumn = !isNaN(rows[0].cells[columnIndex].innerText.trim());

            rows.sort((a, b) => {
                const cellA = a.cells[columnIndex].innerText.trim();
                const cellB = b.cells[columnIndex].innerText.trim();
                
                if (isNumericColumn) {
                    return parseFloat(cellA) - parseFloat(cellB);
                } else {
                    return cellA.localeCompare(cellB);
                }
            });

            if (table.querySelectorAll('thead th')[columnIndex].classList.contains('sorted-asc')) {
                rows.reverse();
                table.querySelectorAll('thead th').forEach(th => th.classList.remove('sorted-asc', 'sorted-desc'));
                table.querySelectorAll('thead th')[columnIndex].classList.add('sorted-desc');
            } else {
                table.querySelectorAll('thead th').forEach(th => th.classList.remove('sorted-asc', 'sorted-desc'));
                table.querySelectorAll('thead th')[columnIndex].classList.add('sorted-asc');
            }

            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }
        function secureUrl(url) {
            if (window.location.protocol === 'https:' && url.startsWith('http:')) {
                return url.replace('http:', 'https:');
            }
            return url;
        }

        function addGiangVien(maGiangVien) {
            // Xử lý logic thêm giảng viên vào bảng phân công
            axios.post(secureUrl("{{ route('admin.quan-ly.bai-thi.handle-them-phan-cong-giang-vien') }}"), {
                ma_giang_vien: maGiangVien,
                ma_lop_hoc_phan: '{{ $maLopHocPhan }}',
                ma_bai_thi: '{{ $maBaiThi }}',
                lan_thi: '{{ $lanThi }}',
                thoi_gian_bat_dau: '{{ $thoiGianBatDau }}',
                thoi_gian_ket_thuc: '{{ $thoiGianKetThuc }}'
            })
            .then(response => {
                if (response.data.success) {
                    if (!response.data.is_last_page) {
                        window.location.reload();
                        return;
                    }
                }
                Swal.fire({
                    icon: response.data.type,
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }


        function deleteGiangVien(maGiangVien) {
           // Xử lý logic thêm giảng viên vào bảng phân công
           axios.post(secureUrl("{{ route('admin.quan-ly.bai-thi.handle-xoa-phan-cong-giang-vien') }}"), {
                ma_giang_vien: maGiangVien,
                ma_lop_hoc_phan: '{{ $maLopHocPhan }}',
                ma_bai_thi: '{{ $maBaiThi }}',
                lan_thi: '{{ $lanThi }}',
                thoi_gian_bat_dau: '{{ $thoiGianBatDau }}',
                thoi_gian_ket_thuc: '{{ $thoiGianKetThuc }}'
            })
            .then(response => {
                if (response.data.success) {
                    if (!response.data.is_last_page) {
                        window.location.reload();
                        return;
                    }
                }
                Swal.fire({
                    icon: response.data.type,
                    title: response.data.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                    showConfirmButton: false,
                    timer: 1500
                })
            });
        }
    </script>
@endsection
