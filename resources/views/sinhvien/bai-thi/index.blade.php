@extends('sinhvien.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between font-bold">
        <div class="mr-5">
            Bài thi
        </div>
    </div>
@endsection
@section('content')
    <div class="">
        <div class="container mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($thongTinBaiThi as $baiThi)
                    @php
                        $currentDateTime = now();
                        $startDateTime = \Carbon\Carbon::createFromFormat('l, d/m/Y, H:i', $baiThi['thoi_gian_bat_dau']);
                        $endDateTime = \Carbon\Carbon::createFromFormat('l, d/m/Y, H:i', $baiThi['thoi_gian_ket_thuc']);
                        $isDisabled = $currentDateTime->lt($startDateTime) || $currentDateTime->gt($endDateTime);
                    @endphp
                   <a class="col-span-1 mt-4" href="{{ !$isDisabled ? route('sinh-vien.quan-ly.bai-thi.quan-ly-lam-bai-thi-sinh-vien', ['id' => $id, 'maLopHocPhan' => $maLopHocPhan,'maBaiThi' => $baiThi['ma_bai_thi'],  'lanThi' => $baiThi['lan_thi']]) : '#' }}" @if($isDisabled) disabled @endif>
                        <div class="border border-gray-300 rounded-md shadow bg-white">
                            <div class="text-gray-500 pb-2">
                                <h2 class="text-lg font-bold border-b p-2 bg-blue-200">{{ $baiThi['ten_bai_thi'] }} - Lần {{ $baiThi['lan_thi'] }}</h2>
                                <p class="mt-2 text-base font-semibold px-2">Bắt đầu: <span class=" text-green-400">{{ $baiThi['thoi_gian_bat_dau'] }}</span></p>
                                <p class="mt-2 text-base font-semibold px-2">Kết thúc: <span class="text-red-400">{{ $baiThi['thoi_gian_ket_thuc'] }}</span></p>
                                <p class="mt-2 text-base font-semibold px-2">Số câu: {{ $baiThi['tongSoCauHoi'] }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
{{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script type="text/javascript">
    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var sessionId = "{{ $sessionId }}";
        function checkSession() {
            
            var currentSessionId = "{{ session()->getId() }}";
            var state = currentSessionId === sessionId;
            if (currentSessionId === sessionId) {
                console.log('Phiên đăng nhập chưa hết hạn');
                
            } else {
                axios.post(secureUrl("{{ route('check-session') }}"),{
                        id: "{{ $id }}",
                        state: state,
                })
                .then(function(response) {
                    if (response.data.success) {
                        window.location.replace(response.data.redirect);
                        return;
                    }
                    Swal.fire({
                        icon: response.data.type,
                        title: response.data.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                })
                .catch(function(error) {
                    console.error(error);
                });
            }
        }
        sessionStorage.setItem('sessionId', sessionId);
        var aTags = document.querySelectorAll('a');
            aTags.forEach(function(aTag) {
                aTag.addEventListener('click', function(event) {
                    checkSession();
                });
            });
    })
</script> --}}