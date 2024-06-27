@extends('sinhvien.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('page-title')
    <div class="flex items-center justify-between mt-4">
        <div class="mr-5 w-full border bg-white h-[120px] flex items-center px-4 text-4xl">
            {{ $tenBaiThi }} - Lần {{ $lanThi }}
        </div>
    </div>
@endsection
@section('content')
    <div class="w-full mt-5">
        <div class="mr-5 border bg-white h-[600px]  px-4 text-xl p-4">
            <div class="mb-6">
                <p class="font-bold">Mô tả:</p>
                @php
                    $moTaSentences = explode('.', $moTa);
                    foreach ($moTaSentences as $sentence) {
                        echo "<p><strong>$sentence</strong></p>";
                    }
                @endphp
            </div>
            <div class="flex flex-col items-center justify-center  ">
                <div class="mb-5">
                    Attemp allowed: 1
                </div>
                <div class="mb-5">
                    Time limit: {{ $sogio}} h {{ $sophut }} p
                </div>
                @if(\Carbon\Carbon::now()->gte($thoiGianKetThucThi) || $sinhVienState === 'true' )
                    <button class="px-4 py-2 rounded-md border border-neutral-300 bg-neutral-100 text-neutral-500 text-sm hover:-translate-y-1 transform transition duration-200 hover:shadow-md" disabled>
                        Can't Attemp quiz now
                    </button>
                @else
                    <a href="{{ route('sinh-vien.quan-ly.bai-thi.quan-ly-lam-bai-thi-trac-nghiem-sinh-vien', ['id' => $id, 'maLopHocPhan' => $maLopHocPhan,'maBaiThi' => $maBaiThi, 'lanThi' => $lanThi]) }}" class="px-4 py-2 rounded-md border border-neutral-300 bg-neutral-100 text-neutral-500 text-sm hover:-translate-y-1 transform transition duration-200 hover:shadow-md">
                        Attemp quiz now
                    </a>
                @endif
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