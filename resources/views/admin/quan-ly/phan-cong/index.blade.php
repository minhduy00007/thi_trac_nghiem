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
            <div class="text-lg font-semibold">
                Danh sách bài thi của lớp học phần: {{ $lopHocPhan->ten_lop_hoc_phan }}
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="flex justify-end mb-2">
    <input type="text" id="searchInput" class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-400" placeholder="Tìm kiếm...">
</div>
<div class="p-2">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @if($danhSachBaiThi->isEmpty())
            <p class="text-center text-gray-600 dark:text-gray-400">Không có bài thi nào cho lớp học phần này.</p>
        @else
            <table id="dataTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(0)">Mã bài thi <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(1)">Tên bài thi <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(2)">Thời gian bắt đầu <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(3)">Thời gian kết thúc <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(4)">Lần thi <span class="sort-arrow">▲▼</span></th>
                        <th class="px-6 py-3">Phân công</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($danhSachBaiThi as $baiThi)
                        <tr  class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-6 py-4">{{ $baiThi->ma_bai_thi }}</td>
                            <td class="px-6 py-4">{{ $baiThi->ten_bai_thi }}</td>
                            <td class="px-6 py-4">{{ $baiThi->thoi_gian_bat_dau }}</td>
                            <td class="px-6 py-4">{{ $baiThi->thoi_gian_ket_thuc }}</td>
                            <td class="px-6 py-4">{{ $baiThi->lan_thi }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.quan-ly.bai-thi.bang-phan-cong-giang-vien', [$lopHocPhan->ma_lop_hoc_phan,  $baiThi->ma_bai_thi, $baiThi->lan_thi ]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Phân công
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $danhSachBaiThi->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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

    </script>
@endsection
