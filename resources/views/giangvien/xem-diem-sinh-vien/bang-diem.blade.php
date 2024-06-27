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
                Điểm sinh viên
            </div> 
        </div>
        <div class="font-normal text-sm mr-5 mt-6">
            <button id="publicScoresBtn" class="px-4 py-2 rounded-md focus:outline-none border border-red-500 hover:bg-red-500 hover:text-white transition duration-300 ease-in-out">Public điểm</button>
            <button id="unpublicScoresBtn" class="ml-2 px-4 py-2 rounded-md focus:outline-none border border-gray-500 hover:bg-gray-500 hover:text-white transition duration-300 ease-in-out">Xóa Public</button>
        </div>
    </div>
@endsection
@section('content')
    <div class="p-2">
        
        <div class="flex justify-end mb-2">
            <input type="text" id="search" class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-400" placeholder="Tìm kiếm...">
        </div>
        <div class="mb-4">
            <button id="showTableBtn" class="px-4 py-2 mr-2 rounded-md focus:outline-none border border-blue-500  hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out">Bảng Điểm</button>
            <button id="showChartBtn" class="px-4 py-2 rounded-md focus:outline-none border border-green-500  hover:bg-green-500 hover:text-white transition duration-300 ease-in-out">Đồ Thị Điểm</button>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Mã sinh viên
                            <button class="sort-btn" data-column="ma_sinh_vien" data-order="asc">&#9650;</button>
                            <button class="sort-btn" data-column="ma_sinh_vien" data-order="desc">&#9660;</button>
                        </th>
                        <th class="px-6 py-3">Tên sinh viên
                            <button class="sort-btn" data-column="ten_sinh_vien" data-order="asc">&#9650;</button>
                            <button class="sort-btn" data-column="ten_sinh_vien" data-order="desc">&#9660;</button>
                        </th>
                        <th class="px-6 py-3">Điểm
                            <button class="sort-btn" data-column="diem" data-order="asc">&#9650;</button>
                            <button class="sort-btn" data-column="diem" data-order="desc">&#9660;</button>
                        </th>
                        <th class="px-6 py-3">Số câu đúng
                            <button class="sort-btn" data-column="so_cau_dung" data-order="asc">&#9650;</button>
                            <button class="sort-btn" data-column="so_cau_dung" data-order="desc">&#9660;</button>
                        </th>
                        <th class="px-6 py-3">Xem chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($danhSachSinhVien as $duLieu)
                        @php
                            $soCauDung = json_decode($duLieu['so_cau_tra_loi_dung'], true)['so_cau_dung'];
                        @endphp
                        <tr>
                            <td class="px-6 py-4">{{ $duLieu['ma_sinh_vien'] }}</td>
                            <td class="px-6 py-4">{{ $duLieu['ten_sinh_vien'] }}</td>
                            <td class="px-6 py-4 student-score">{{ $duLieu['diem'] }}</td>
                            <td class="px-6 py-4">{{ $soCauDung }}</td>
                            <td class="px-6 py-4">
                                <button class="view-details-btn px-4 py-2 rounded-md focus:outline-none border border-blue-500 hover:bg-blue-500 hover:text-white transition duration-300 ease-in-out" data-ma-sinh-vien="{{ $duLieu['ma_sinh_vien'] }}"  data-ma-bai-thi="{{ $maBaiThi}}" data-ma-lop-hoc-phan="{{ $maLopHocPhan }}">Xem chi tiết</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $danhSachSinhVien->links() }}
            </div>
        </div>
    </div>
    <div id="chartContainer" class="mt-4" style="height: 300px;"></div>
@endsection
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@section('page-js')
     <script type="text/javascript">
      document.addEventListener('DOMContentLoaded', function() {
            var currentChart = null; // Biến để lưu trữ đồ thị hiện tại

            // Button Hiển thị Bảng Điểm
            document.getElementById('showTableBtn').addEventListener('click', function() {
                document.querySelector('.overflow-x-auto').style.display = 'block';
                if (currentChart) {
                    currentChart.destroy(); // Hủy đồ thị hiện tại nếu có
                    currentChart = null; // Đặt lại biến đồ thị
                }
                document.getElementById('chartContainer').style.display = 'none';
            });

            // Button Hiển thị Đồ Thị Điểm
            document.getElementById('showChartBtn').addEventListener('click', function() {
                document.querySelector('.overflow-x-auto').style.display = 'none';
                document.getElementById('chartContainer').style.display = 'block';

                // Hủy đồ thị hiện tại nếu có
                if (currentChart) {
                    currentChart.destroy();
                }

                // Tính toán số lượng câu đúng của sinh viên
                var correctAnswers = {};
                var correctAnswerElements = document.querySelectorAll('tbody tr td:nth-child(4)'); // Lấy các phần tử thể hiện số câu đúng

                correctAnswerElements.forEach(function(element) {
                    var numCorrect = parseInt(element.textContent.trim());
                    if (!isNaN(numCorrect)) {
                        if (correctAnswers[numCorrect]) {
                            correctAnswers[numCorrect]++;
                        } else {
                            correctAnswers[numCorrect] = 1;
                        }
                    }
                });

                // Chuẩn bị dữ liệu để vẽ đồ thị
                var xLabels = Object.keys(correctAnswers); // Sử dụng số lượng câu đúng làm labels trên trục x
                var yData = Object.values(correctAnswers);

                // Vẽ đồ thị bằng thư viện ApexCharts
                var chartOptions = {
                    chart: {
                        type: 'bar',
                        height: 300,
                        toolbar: {
                            show: false
                        }
                    },
                    series: [{
                        name: 'Số lượng',
                        data: yData
                    }],
                    xaxis: {
                        categories: xLabels.map(String), // Chuyển đổi các nhãn thành chuỗi để sử dụng làm labels trên trục x
                        title: {
                            text: 'Số lượng câu đúng'
                        }
                    },
                    yaxis: {
                        min: 0,
                        max: 10, // Đặt giá trị tối thiểu của trục y là 0
                        title: {
                            text: 'Điểm'
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                position: 'top' // Hiển thị nhãn trên cột
                            }
                        }
                    }
                };

                // Tạo và render đồ thị mới
                currentChart = new ApexCharts(document.querySelector('#chartContainer'), chartOptions);
                currentChart.render();
            });

            // Xem chi tiết kết quả bài thi của sinh viên
            document.querySelectorAll('.view-details-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var maSinhVien = this.getAttribute('data-ma-sinh-vien');
                    var maBaiThi = this.getAttribute('data-ma-bai-thi');
                    var maLopHocPhan = this.getAttribute('data-ma-lop-hoc-phan');

                    axios.get(secureUrl("{{ route('giang-vien.quan-ly.xem-diem.chi-tiet-bai-thi') }}"), {
                        params: {
                            maSinhVien: maSinhVien,
                            maBaiThi: maBaiThi,
                            maLopHocPhan: maLopHocPhan,
                            lanThi: {{ $lanThi }},
                        }
                    })
                    .then(function(response) {
                        // Xử lý phản hồi từ yêu cầu GET
                        console.log(response.data);
                        // Ví dụ: chuyển hướng đến trang chi tiết bài thi
                        window.location.href = response.request.responseURL;
                    })
                    .catch(function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    });
                });
            });
        });

        function getColumnIndex(columnName) {
            const headers = document.querySelectorAll('thead th');
            let index = 0;

            headers.forEach((header, i) => {
                const spanText = header.querySelector('span').textContent.trim();
                if (spanText === columnName) {
                    index = i + 1; // Bắt đầu từ 1 vì nth-child sử dụng chỉ số bắt đầu từ 1
                }
            });

            return index;
        }

        function secureUrl(url) {
            if (window.location.protocol === 'https:' && url.startsWith('http:')) {
                return url.replace('http:', 'https:');
            }
            return url;
        }

        document.getElementById('publicScoresBtn').addEventListener('click', function() {
            var maBaiThi = '{{ $maBaiThi }}'; // Lấy mã bài thi từ biến đã truyền qua view
            axios.post(secureUrl("{{ route('giang-vien.quan-ly.xem-diem.publicDiem') }}"), {
                maBaiThi: maBaiThi,
            })
            .then(function(response) {
                if (response.data.success) {
                    alert(response.data.message);
                } else {
                    alert('Đã xảy ra lỗi.');
                }
            })
            .catch(function(error) {
                console.error(error);
                alert('Đã xảy ra lỗi.');
            });
        });

        document.getElementById('unpublicScoresBtn').addEventListener('click', function() {
            var maBaiThi = '{{ $maBaiThi }}'; // Lấy mã bài thi từ biến đã truyền qua view

            axios.post(secureUrl("{{ route('giang-vien.quan-ly.xem-diem.unpublicDiem') }}"), {
                maBaiThi: maBaiThi,
            })
            .then(function(response) {
                if (response.data.success) {
                    alert(response.data.message); // Hiển thị thông báo thành công
                } else {
                    alert('Đã xảy ra lỗi.'); // Hiển thị thông báo lỗi
                }
            })
            .catch(function(error) {
                console.error(error);
                alert('Đã xảy ra lỗi.'); // Hiển thị thông báo lỗi
            });
        });

    </script> 
@endsection
