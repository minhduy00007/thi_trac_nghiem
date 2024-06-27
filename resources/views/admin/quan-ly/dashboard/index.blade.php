@extends('layouts.master')

@section('title', 'Dashboard Admin')

@section('page-title')
<div class="flex items-center justify-between">
    <div class="flex items-center">
        <div class="text-xl font-bold mr-5">
            Dashboard Admin
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="p-4">
    <div class="container mx-auto mt-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-100 rounded-lg shadow-md p-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-blue-500 text-white flex items-center justify-center h-12 w-12">
                        <svg class="h-8 w-8 text-white" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-semibold">Số lượng sinh viên</div>
                        <div class="text-xl">{{ $soLuongSinhVien }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-green-100 rounded-lg shadow-md p-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-green-500 text-white flex items-center justify-center h-12 w-12">
                        <svg class="h-8 w-8 text-white" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M96 32C60.7 32 32 60.7 32 96V384H96V96l384 0V384h64V96c0-35.3-28.7-64-64-64H96zM224 384v32H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H544c17.7 0 32-14.3 32-32s-14.3-32-32-32H416V384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-semibold">Số lượng lớp học phần</div>
                        <div class="text-xl">{{ $soLuongLopHocPhan }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-yellow-100 rounded-lg shadow-md p-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-yellow-500 text-white flex items-center justify-center h-12 w-12">
                        <svg class="h-8 w-8 text-white" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9v28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5V291.9c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-semibold">Số lượng giảng viên</div>
                        <div class="text-xl">{{ $soLuongGiangVien }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-red-100 rounded-lg shadow-md p-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="rounded-full bg-red-500 text-white flex items-center justify-center h-12 w-12">
                        <svg class="h-8 w-8 text-white" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 80v48c0 17.7 14.3 32 32 32H48 96V80c0-26.5-21.5-48-48-48S0 53.5 0 80zM112 32c10 13.4 16 30 16 48V384c0 35.3 28.7 64 64 64s64-28.7 64-64v-5.3c0-32.4 26.3-58.7 58.7-58.7H480V128c0-53-43-96-96-96H112zM464 480c61.9 0 112-50.1 112-112c0-8.8-7.2-16-16-16H314.7c-14.7 0-26.7 11.9-26.7 26.7V384c0 53-43 96-96 96H368h96z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-semibold">Số lượng bài thi</div>
                        <div class="text-xl">{{ $soLuongBaiThi }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script type="text/javascript">
    // Your JavaScript code, if needed
</script>
@endsection
