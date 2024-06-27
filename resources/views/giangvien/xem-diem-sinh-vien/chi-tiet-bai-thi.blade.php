<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết bài thi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-white">
    <header class="bg-blue-500 p-4 flex items-center">
        <div class="ml-16 mr-5">
            <img src="{{ asset('images/logo_vlu.png') }}" alt="Logo Trường" class="h-12">
        </div>
        <div class="text-white font-semibold">Trường Đại học Văn Lang</div>
    </header>

    <div class="container mx-auto mt-8">
        <div >
            <button onclick="goBack()" class="btn btn-blue text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">Quay lại</button>
        </div>
        <div class="border border-gray-300 p-4">
            <div class="mb-4">
                <h2 class="text-4xl font-semibold text-red-500">Tên bài thi: {{ $tenBaiThi }}</h2>
                <p class="text-lg text-gray-600">Tên lớp học phần: {{ $tenLopHocPhan }}</p>
                <p class="text-lg text-gray-600">Mã lớp học phần: {{ $maLopHocPhan }}</p>
            </div>
        </div>
    </div>
    
    <div class="container mx-auto mt-8">
        <div class="flex flex-col md:flex-row md:space-x-3">
            <div class="questions w-full md:w-4/6">
                @foreach ($chiTietBaiThi as $index => $cauHoi)
                    <div class="question border border-gray-300 p-4 mb-4" id="question{{ $index + 1 }}">
                        <p class="mb-2">Câu hỏi {{ $index + 1 }}</p>
                        <p class="mb-2">{{ $cauHoi['cau_hoi'] }}</p>
                        <ul>
                            @foreach ($cauHoi['cau_tra_loi'] as $i => $cauTraLoiText)
                                <li class="{{ in_array($i, $cauHoi['dap_an_dung']) ? 'text-green-600' : '' }}">
                                    @if (count($cauHoi['dap_an_dung']) > 1)
                                        <input type="checkbox" id="cauTraLoi{{$i+1}}" name="cauTraLoi{{ $index + 1 }}" value="{{ $cauTraLoiText }}" @if (isset($cauTraLoi[$index]['dapAnChon']) && in_array($cauTraLoiText, $cauTraLoi[$index]['dapAnChon'])) checked @endif disabled>
                                    @else
                                        <input type="radio" id="cauTraLoi{{$i+1}}" name="cauTraLoi{{ $index + 1 }}" value="{{ $cauTraLoiText }}" @if (isset($cauTraLoi[$index]['dapAnChon']) && in_array($cauTraLoiText, $cauTraLoi[$index]['dapAnChon'])) checked @endif disabled>
                                    @endif
                                    <label for="cauTraLoi{{$i+1}}">{{ chr(65 + $i) }}. {{ $cauTraLoiText }}</label>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-2">
                            <div class="flex">
                                <strong class="mr-2">Đáp án đúng: </strong>
                                <ul class="text-green-600">
                                    @foreach ($cauTraLoi[$index]['dapAnDung'] as $dapAnDung)
                                        <li>{{ $dapAnDung }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <strong>Trạng thái:</strong>
                            <span class="{{ $cauTraLoi[$index]['dungSai'] ? 'text-green-600' : 'text-red-600' }}">
                                {{ $cauTraLoi[$index]['dungSai'] ? 'Đúng' : 'Sai' }}
                            </span>
                        </div>
                    </div>
                @endforeach
                <div class="pagination mt-4 flex justify-between">
                    @if ($totalPages > 1)
                        <button onclick="previousPage()" id="btnPrevious" class="btn btn-blue mr-2 text-gray-900 hover:text-white border border-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800"><< Previous page</button>
                        <button onclick="nextPage()" id="btnNext" class="btn btn-blue text-red-400 hover:text-white border border-red-400 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-500 dark:focus:ring-red-600">Next page >></button>
                    @endif
                </div>
            </div>
            <div class="quiz-nav bg-white border-2 shadow mt-3 md:mt-0 md:w-2/6 h-[300px] md:h-[480px] rounded-lg overflow-auto">
                <div class="text-lg text-red-600 font-semibold mb-7 border-b-2 flex items-center"><p class="ml-6 mb-3">Quiz navigation</p></div>
                <div class="pl-6 flex flex-wrap items-center">
                    @for ($i = 1; $i <= $totalQuestions; $i++)
                        @php
                            $questionIndex = $i - 1;
                            $questionStatusClass = isset($cauTraLoi[$questionIndex]['dapAnChon']) ? (count($cauTraLoi[$questionIndex]['dapAnChon']) > 0 ? 'bg-green-400 text-white' : '') : '';
                        @endphp
                        <button onclick="goToQuestion({{ $i }})" class="quiz-nav-btn border-2 px-3 py-2 rounded-lg mb-2 mr-2 w-10 text-center {{ $questionStatusClass }}" data-question="{{ $i }}">{{ $i }}</button>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script src="https://cdn.tailwindcss.com"></script>
<script type="text/javascript">
    let currentPage = 1;
    const totalPages = {{ $totalPages }};
    const btnPrevious = document.getElementById('btnPrevious');
    const btnNext = document.getElementById('btnNext');
    const totalQuestions = {{ $totalQuestions }};
    // Function to show questions based on current page
    function showPage(page) {
        const questionsPerPage = 5;
        const startIndex = (page - 1) * questionsPerPage;
        const endIndex = Math.min(startIndex + questionsPerPage, totalQuestions);
        for (let i = 1; i <= totalQuestions; i++) {
            const question = document.getElementById(`question${i}`);
            if (question) {
                question.style.display = (i >= startIndex + 1 && i <= endIndex) ? 'block' : 'none';
            }
        }
        updateButtonsVisibility();
    }

    // Function to handle saving current page when navigating
    function saveCurrentPage(page) {
        localStorage.setItem('currentPage', page);
    }

    // Load current page from local storage if available
    document.addEventListener('DOMContentLoaded', () => {
        const savedPage = localStorage.getItem('currentPage');
        if (savedPage) {
            currentPage = parseInt(savedPage);
        }
        showPage(currentPage);
    });
    btnPrevious.addEventListener('click', () => saveCurrentPage(currentPage));
    btnNext.addEventListener('click', () => saveCurrentPage(currentPage));
    function previousPage() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
                updateButtonsVisibility(); // Lưu trạng thái trang hiện tại
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
                updateButtonsVisibility(); // Lưu trạng thái trang hiện tại
            }
        }

        function goToQuestion(questionNumber) {
            currentPage = Math.ceil(questionNumber / 5);
            showPage(currentPage);
            updateButtonsVisibility();

            // Scroll to the selected question
            const questionElement = document.getElementById(`question${questionNumber}`);
            if (questionElement) {
                questionElement.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function updateButtonsVisibility() {
            btnPrevious.style.display = (currentPage > 1) ? 'inline-block' : 'none';
            btnNext.style.display = (currentPage < totalPages) ? 'inline-block' : 'none';
        }
        // Hàm quay lại trang trước
        function goBack() {
            window.history.back();
        }
</script>