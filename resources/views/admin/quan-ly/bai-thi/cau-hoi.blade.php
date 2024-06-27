@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between sticky top-0">
        <div class="mr-5">
            {{ $title }}
        </div>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="border-b mb-2 flex justify-between sticky top-0">
            <span class="text-lg mr-3 ml-2">
                Danh sách câu hỏi
            </span>
            <div class="flex items-center">
                <p class="text-md mr-3 ml-2">Thêm câu hỏi:</p>
                
                <div class="flex items-center"> 
                    <button class="relative inline-flex items-center justify-center overflow-hidden p-0.5 mb-2 me-2 " title="Tải file excel mẫu">
                        <a id="download-link" class="inline-block"> 
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-10 h-10"> 
                                <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM216 232V334.1l31-31c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-72 72c-9.4 9.4-24.6 9.4-33.9 0l-72-72c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l31 31V232c0-13.3 10.7-24 24-24s24 10.7 24 24z" fill="#008000"/> <!-- Thay đổi fill="#008000" để đổi màu thành xanh lá cây -->
                            </svg>
                        </a>
                    </button>
                    <button onclick="themCauHoiMotDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                        <span class="relative px-2 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                            Một đáp án
                        </span>
                    </button>
                    <button onclick="themCauHoiNhieuDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                        <span class="relative px-2 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                            Nhiều đáp án
                        </span>
                    </button>
                    <div class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                        <label for="file-upload" class="relative px-2 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0 cursor-pointer">
                            <input id="file-upload" type="file" accept=".xlsx, .xls" class="hidden" onchange="importQuestions(event)">
                            <span>Import File</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div id="list-cau-hoi">
            @if($danhSachCauHoi)
                    @foreach($danhSachCauHoi as $key => $cauHoi)
                        <div id="cau-hoi-{{ $key + 1 }}" class="cau-hoi">
                            <div class="tao-cau-hoi w-full flex p-4 border-2 rounded-lg shadow-black-50 mb-3">
                                <div class="w-1/6 mr-1">
                                    Câu hỏi <span  class="so-thu-tu">{{ $key + 1 }}</span>:
                                </div>
                                <div class="w-5/6">
                                    <div class="mb-2 relative">
                                        <label for="content" class="block text-sm font-medium text-gray-700">Câu hỏi</label>
                                        <textarea id="content-{{ $key + 1 }}" name="content-{{ $key + 1 }}" rows="3" class="w-full px-3 py-2 mt-1 text-sm text-gray-700 placeholder-gray-400 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Nhập câu hỏi ở đây...">{{ $cauHoi['cau_hoi'] }}</textarea>
                                        <button onclick="xoaCauHoi('cau-hoi-{{ $key + 1 }}')" class="xoa-cau-hoi absolute right-0 top-0 mr-2 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="flex items-start flex-wrap">
                                        <div class="w-full flex items-center">
                                            <div class="w-2/3">
                                                Danh sách đáp án
                                            </div>
                                            <div class="w-1/3 ml-5">
                                                Đáp án đúng
                                            </div>
                                        </div>
                                        <div id='' class="w-full list-cau-tra-loi">
                                            @foreach($cauHoi['cau_tra_loi'] as $index => $cauTraLoi)
                                                    <div class="w-full flex items-center mb-2 list-group-items">
                                                        <div class="w-2/3 flex">
                                                            <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" value="{{ $cauTraLoi }}" oninput="nhapCauTraLoi()">
                                                        </div>
                                                        <div class="w-1/3">
                                                            <span class="ml-10">
                                                                @if(count($cauHoi['dap_an_dung']) == 1)
                                                                    <input type="radio" class="input-dap-an" name="group-{{ $key + 1 }}" @if($index == $cauHoi['dap_an_dung'][0]) checked @endif>
                                                                @else
                                                                    <input type="checkbox" class="input-dap-an" name="group-{{ $key + 1 }}" @if(in_array($index, $cauHoi['dap_an_dung'])) checked @endif>
                                                                @endif
                                                                <button onclick="xoaCauTraLoi()" class="ml-14">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                                    </svg>
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="w-full ">
                                        @if(count($cauHoi['dap_an_dung']) == 1)
                                            <button onclick="themCauTraLoiMotDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                                                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                                    Thêm
                                                </span>
                                            </button>
                                        @else
                                            <button onclick="themCauTraLoiNhieuDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                                                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                                    Thêm
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
            @else
                <div id="cau-hoi-1" class="cau-hoi">
                    <div class="tao-cau-hoi w-full flex p-4 border-2 rounded-lg shadow-black-50 mb-3">
                        <div class="w-1/6 mr-1">
                            Câu hỏi <span class="so-thu-tu">1</span>:
                        </div>
                        <div class="w-5/6">
                            <div class="mb-2 relative">
                                <label for="content" class="block text-sm font-medium text-gray-700">Câu hỏi</label>
                                <textarea id="content-1" name="content-1" rows="3" class="w-full px-3 py-2 mt-1 text-sm text-gray-700 placeholder-gray-400 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Nhập câu hỏi ở đây..."></textarea>
                                <button onclick="xoaCauHoi('cau-hoi-1')" class="xoa-cau-hoi absolute right-0 top-0 mr-2 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-start flex-wrap">
                                <div class="w-full flex items-center">
                                    <div class="w-2/3">
                                        Danh sách đáp án
                                    </div>
                                    <div class="w-1/3 ml-5">
                                        Đáp án đúng
                                    </div>
                                </div>
                                <div id='' class="w-full list-cau-tra-loi">
                                    <div class="w-full flex items-center mb-2 list-group-items">
                                        <div class="w-2/3 flex">
                                            <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                        </div>
                                        <div class="w-1/3">
                                            <span class="ml-10">
                                                <input type="radio" class="input-dap-an" name="group-1">
                                                <button onclick="xoaCauTraLoi()" class="ml-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>                                          
                                                </button>
                                            </span>
                                        </div>                            
                                    </div>
                                    <div class="w-full flex items-center mb-2 list-group-items">
                                        <div class="w-2/3 flex">
                                            <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                        </div>
                                        <div class="w-1/3">
                                            <span class="ml-10">
                                                <input type="radio" class="input-dap-an" name="group-1">
                                                <button onclick="xoaCauTraLoi()" class="ml-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>                                          
                                                </button>
                                            </span>
                                        </div>                            
                                    </div>
                                    <div class="w-full flex items-center mb-2 list-group-items">
                                        <div class="w-2/3 flex">
                                            <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                        </div>
                                        <div class="w-1/3">
                                            <span class="ml-10">
                                                <input type="radio" class="input-dap-an" name="group-1">
                                                <button onclick="xoaCauTraLoi()" class="ml-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>                                          
                                                </button>
                                            </span>
                                        </div>                            
                                    </div>
                                    <div class="w-full flex items-center mb-2 list-group-items">
                                        <div class="w-2/3 flex">
                                            <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                        </div>
                                        <div class="w-1/3">
                                            <span class="ml-10">
                                                <input type="radio" class="input-dap-an" name="group-1">
                                                <button onclick="xoaCauTraLoi()" class="ml-14">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>                                          
                                                </button>
                                            </span>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            
                            <div class="w-full ">
                                <button onclick="themCauTraLoiMotDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                                    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                    Thêm
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <div class="bottom-0 bg-white shadow-lg p-4">
            <div class="flex justify-end">
                <button onclick="luu()" class="mr-3 border-2 border-emerald-500 py-2 px-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                    Lưu
                </button>
            </div>
        </div>

    </div>

@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
@section('page-js')
     <script type="text/javascript">
        var listCauHoi = document.getElementById("list-cau-hoi").innerHTML;
        function themCauTraLoiMotDapAn() {
            var button = event.target;
            var cauHoi = button.closest('.cau-hoi');
            var number = cauHoi.id.split('-')[2];
            var parentElement = cauHoi.querySelector('.list-cau-tra-loi')
            var newDiv = document.createElement('div');
            newDiv.classList.add('list-group-items', 'w-full', 'flex', 'items-center', 'mb-2');
            newDiv.innerHTML = `<div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="radio" class="input-dap-an" name="group-${number}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>               `
            parentElement.appendChild(newDiv)
        }

        function themCauTraLoiNhieuDapAn() {
            var button = event.target;
            var cauHoi = button.closest('.cau-hoi');
            var number = cauHoi.id.split('-')[2];
            var parentElement = cauHoi.querySelector('.list-cau-tra-loi')
            console.log(parentElement);
            var newDiv = document.createElement('div');
            newDiv.classList.add('list-group-items', 'w-full', 'flex', 'items-center', 'mb-2');
            newDiv.innerHTML = `<div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="checkbox" class="input-dap-an" name="group-${number}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>               `
            parentElement.appendChild(newDiv)
        }

        function themCauHoiMotDapAn() {
            var parentElement = document.getElementById("list-cau-hoi")
            var newDiv = document.createElement('div');
            var children = parentElement.children
            console.log(children.length)
            newDiv.id = `cau-hoi-${children.length + 1}`
            newDiv.classList.add(`cau-hoi`);
            newDiv.innerHTML = `
                <div class="tao-cau-hoi w-full flex p-4 border-2 rounded-lg shadow-black-50 mb-3">
                    <div class="w-1/6 mr-1">
                        Câu hỏi <span class="so-thu-tu">${children.length + 1}</span>:
                    </div>
                    <div class="w-5/6">
                        <div class="mb-2 relative">
                            <label for="content" class="block text-sm font-medium text-gray-700">Câu hỏi</label>
                            <textarea id="content-${children.length + 1}" name="content-${children.length + 1}" rows="3" class="w-full px-3 py-2 mt-1 text-sm text-gray-700 placeholder-gray-400 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Nhập câu hỏi ở đây..."></textarea>
                            <button onclick="xoaCauHoi('cau-hoi-${children.length + 1}')" class="xoa-cau-hoi absolute right-0 top-0 mr-2 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-start flex-wrap">
                            <div class="w-full flex items-center">
                                <div class="w-2/3">
                                    Danh sách đáp án
                                </div>
                                <div class="w-1/3 ml-5">
                                    Đáp án đúng
                                </div>
                            </div>
                            <div id='' class="w-full list-cau-tra-loi">
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="radio" class="input-dap-an" name="group-${children.length + 1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="radio" class="input-dap-an" name="group-${children.length + 1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="radio" class="input-dap-an" name="group-${children.length + 1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="radio" class="input-dap-an" name="group-${children.length + 1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        
                        <div class="w-full ">
                            <button onclick="themCauTraLoiMotDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                Thêm
                                </span>
                            </button>
                        </div>
                    </div>
                </div>`
            parentElement.appendChild(newDiv)

        }

        function themCauHoiNhieuDapAn() {
            var parentElement = document.getElementById("list-cau-hoi")
            var newDiv = document.createElement('div');
            var children = parentElement.children
            newDiv.id = `cau-hoi-${children.length + 1}`
            newDiv.classList.add(`cau-hoi`);
            newDiv.innerHTML = `
                <div class="tao-cau-hoi w-full flex p-4 border-2 rounded-lg shadow-black-50 mb-3">
                    <div class="w-1/6 mr-1">
                        Câu hỏi <span class="so-thu-tu">${children.length + 1}</span>:
                    </div>
                    <div class="w-5/6">
                        <div class="mb-2 relative">
                            <label for="content" class="block text-sm font-medium text-gray-700">Câu hỏi</label>
                            <textarea id="content-${children.length + 1}" name="content-${children.length + 1}" rows="3" class="w-full px-3 py-2 mt-1 text-sm text-gray-700 placeholder-gray-400 border rounded-lg focus:outline-none focus:ring focus:border-blue-300" placeholder="Nhập câu hỏi ở đây..."></textarea>
                            <button onclick="xoaCauHoi('cau-hoi-${children.length + 1}')" class="xoa-cau-hoi absolute right-0 top-0 mr-2 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-start flex-wrap">
                            <div class="w-full flex items-center">
                                <div class="w-2/3">
                                    Danh sách đáp án
                                </div>
                                <div class="w-1/3 ml-5">
                                    Đáp án đúng
                                </div>
                            </div>
                            <div id='' class="w-full list-cau-tra-loi">
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="checkbox" class="input-dap-an" name="group-${children.lenght+1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="checkbox" class="input-dap-an" name="group-${children.lenght+1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="checkbox" class="input-dap-an" name="group-${children.lenght+1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                                <div class="w-full flex items-center mb-2 list-group-items">
                                    <div class="w-2/3 flex">
                                        <input type="text" class="w-full h-10 px-3 border-2 rounded cau-tra-loi" oninput="nhapCauTraLoi()">
                                    </div>
                                    <div class="w-1/3">
                                        <span class="ml-10">
                                            <input type="checkbox" class="input-dap-an" name="group-${children.lenght+1}">
                                            <button onclick="xoaCauTraLoi()" class="ml-14">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                  </svg>                                          
                                            </button>
                                        </span>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        
                        <div class="w-full ">
                            <button onclick="themCauTraLoiNhieuDapAn()" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                                Thêm
                                </span>
                            </button>
                        </div>
                    </div>
                </div>`
            parentElement.appendChild(newDiv)
        }

        function importQuestions(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || file.type === 'application/vnd.ms-excel') {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const data = new Uint8Array(e.target.result);
                        const workbook = XLSX.read(data, { type: 'array' });
                        const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                        const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                        const questions = jsonData.slice(1).map(row => {
                            const answers = row.slice(1, -1).filter(answer => answer !== undefined && answer !== "");

                            // Kiểm tra xem giá trị cuối cùng của mảng row là một chuỗi trước khi split
                            let correctAnswersIndices = [];
                            if (typeof row[row.length - 1] === 'string') {
                                correctAnswersIndices = row[row.length - 1].split(',').map(answerIndex => parseInt(answerIndex.trim()) - 1);
                            } else if (typeof row[row.length - 1] === 'number') {
                                // Trường hợp giá trị cuối cùng là một số, chuyển đổi nó thành mảng chỉ số đáp án đúng
                                correctAnswersIndices = [parseInt(row[row.length - 1]) - 1];
                            } else {
                                // Xử lý lỗi nếu giá trị cuối cùng không phải là chuỗi hoặc số
                                console.error("Giá trị đáp án đúng không hợp lệ:", row[row.length - 1]);
                            }

                            return {
                                cau_hoi: row[0],
                                cau_tra_loi: answers,
                                dap_an_dung: correctAnswersIndices
                            };
                        });

                        var cauHoiId = "{{ $id }}";
                        var data_excel = {
                            data: questions,
                            cauHoiId: cauHoiId,
                        };

                        axios.post(secureUrl("{{ route('admin.quan-ly.bai-thi.handle-them-bai-thi-cau-hoi') }}"), data_excel)
                            .then(function (response) {
                                if (response.data.success) {
                                    window.location.replace(response.data.redirect);
                                } else {
                                    Swal.fire({
                                        icon: response.data.type,
                                        title: response.data.message,
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                }
                            })
                            .catch(function (error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            });
                    };
                    reader.readAsArrayBuffer(file);
                } else {
                    alert('Vui lòng chọn tệp Excel hợp lệ.');
                }
            }
        }


        function xoaCauTraLoi() {
            var button = event.target;
            var parentItem = button.closest('.list-group-items');
            parentItem.remove();
        }

        function nhapCauTraLoi(){
            var inputElement = event.target;
            var cauHoi = inputElement.closest('.cau-hoi');
            var parentElement = cauHoi.querySelector('.list-cau-tra-loi')
            var inputSelect = parentElement.querySelector('.input-dap-an')
            inputSelect.value = inputElement.value;
        }
        function secureUrl(url) {
            if (window.location.protocol === 'https:' && url.startsWith('http:')) {
                return url.replace('http:', 'https:');
            }
            return url;
        }

        function luu() {
            event.preventDefault();

            var listCauHoi = [];

            var childrenElements = document.querySelectorAll('#list-cau-hoi .cau-hoi');
            childrenElements.forEach((cauHoiElement, index) => {
                var cauHoi = cauHoiElement.querySelector('textarea').value.trim();

                var listCauTraLoi = [];
                var dapAnDung = [];
                cauHoiElement.querySelectorAll('.list-group-items').forEach((cauTraLoiElement, index) => {
                    var cauTraLoi = cauTraLoiElement.querySelector('.cau-tra-loi').value.trim();
                    var isDapAnDung = cauTraLoiElement.querySelector('.input-dap-an').checked;

                    if (cauTraLoi !== '') {
                        listCauTraLoi.push(cauTraLoi);
                    }
                    if (isDapAnDung) {
                        dapAnDung.push(index);
                    }
                });

                if (cauHoi !== '' && listCauTraLoi.length > 0 && dapAnDung.length > 0) {
                    listCauHoi.push({
                        cau_hoi: cauHoi,
                        cau_tra_loi: listCauTraLoi,
                        dap_an_dung: dapAnDung
                    });
                }
            });

            var jsonListCauHoi = JSON.stringify(listCauHoi);
            var cauHoiId = "{{ $id }}";

            // Tạo đối tượng data chứa toàn bộ dữ liệu câu hỏi
            var data = {
                data: jsonListCauHoi,
                cauHoiId: cauHoiId,
            };

            // Gửi một yêu cầu AJAX duy nhất
            axios.post(secureUrl("{{ route('admin.quan-ly.bai-thi.handle-them-bai-thi-cau-hoi') }}"), data)
                .then(function (response) {
                    if (response.data.success) {
                        window.location.replace(response.data.redirect);
                    } else {
                        Swal.fire({
                            icon: response.data.type,
                            title: response.data.message,
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                })
                .catch(function (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
        }

        function capNhatSoThuTuCauHoi() {
            var soThuTuElements = document.querySelectorAll('.so-thu-tu');
            var cauHoiDauTien = document.getElementById('cau-hoi-1');
            var soThuTuBatDau = cauHoiDauTien ? 2 : 1;

            soThuTuElements.forEach(function(soThuTuElement, index) {
                var soThuTu = soThuTuBatDau + index;
                soThuTuElement.innerText = soThuTu;
            });
        }


        function xoaCauHoi(cauHoiId) {
            var cauHoiElement = document.getElementById(cauHoiId);
            cauHoiElement.parentNode.removeChild(cauHoiElement);

            // Cập nhật lại số thứ tự của các câu hỏi còn lại
            capNhatSoThuTuCauHoi();

            // Cập nhật tên group của radio và checkbox trong các câu hỏi còn lại
            var danhSachCauHoi = document.querySelectorAll('.cau-hoi');
            danhSachCauHoi.forEach(function(cauHoi, index) {
                var soThuTu = index + 1;
                var contentTextarea = cauHoi.querySelector('textarea');
                var inputElements = cauHoi.querySelectorAll('input[type="radio"], input[type="checkbox"]');

                // Cập nhật số thứ tự của câu hỏi
                var soThuTuElement = cauHoi.querySelector('.so-thu-tu');
                if (soThuTuElement) {
                    soThuTuElement.innerText = soThuTu;
                }

                // Cập nhật tên group của radio và checkbox
                if (contentTextarea && inputElements.length > 0) {
                    var groupName = 'group-' + soThuTu;
                    inputElements.forEach(function(input) {
                        input.setAttribute('name', groupName);
                    });
                }
            });
        }


        // Thêm sự kiện onclick vào nút Xóa của mỗi câu hỏi
        document.addEventListener('DOMContentLoaded', function() {
            var nutXoaCauHoi = document.querySelectorAll('.xoa-cau-hoi ');
            nutXoaCauHoi.forEach(function(button) {
                button.addEventListener('click', function() {
                    var cauHoiId = this.parentNode.parentNode.id;
                    xoaCauHoi(cauHoiId);
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Khôi phục trạng thái khi trang được tải
            var savedContent = localStorage.getItem("content");
            if (savedContent) {
                document.getElementById("content").value = savedContent;
                // Thêm các bước khôi phục khác nếu cần
            }
        });

        window.addEventListener('beforeunload', function() {
            // Lưu trạng thái trang trước khi reload
            var content = document.getElementById("content").value;
            localStorage.setItem("content", content);
            // Thêm các bước lưu trạng thái khác nếu cần
        });

        document.getElementById('download-link').addEventListener('click', function() {
            axios.get(secureUrl("{{ route('admin.quan-ly.bai-thi.download-template') }}"), { responseType: 'blob' })
                .then(function(response) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'template.xlsx');
                    document.body.appendChild(link);
                    link.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(function(error) {
                    console.error('Error downloading file:', error);
                    // Xử lý lỗi nếu cần
                });
        });
        
    </script> 
@endsection