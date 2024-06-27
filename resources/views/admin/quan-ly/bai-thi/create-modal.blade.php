<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-them-bai-thi">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
            <h4 class="text-lg font-semibold">
                Thêm bài thi
            </h4>
        </div>
        <form id="form-them-bai-thi">
            <div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Mã lớp học phần:</label>
                    <select id="input-ma-lop-hoc-phan-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1">
                        @foreach ($danhSachLopHocPhan as $lopHocPhan)
                            <option value="{{ $lopHocPhan->ma_lop_hoc_phan }}">{{ $lopHocPhan->ma_lop_hoc_phan }}</option>
                        @endforeach
                    </select>
                </div>                
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Mã bài thi:</label>
                    <input id="input-ma-bai-thi-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1" type="text">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Tên bài thi:</label>
                    <input id="input-ten-bai-thi-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1" type="text">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Môn học:</label>
                    <select id="input-mon-hoc-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1 overflow-y-auto max-h-40">
                        @foreach ($danhSachMon as $mon)
                            <option value="{{ $mon->ma_mon_hoc }}">{{ $mon->ten_mon_hoc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Thời gian bắt đầu:</label>
                    <input id="input-thoi-gian-bat-dau-bai-thi-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1" type="datetime-local" placeholder="Chọn thời gian bắt đầu">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Thời gian kết thúc:</label>
                    <input id="input-thoi-gian-ket-thuc-bai-thi-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1" type="datetime-local" placeholder="Chọn thời gian kết thúc">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label for="input-lan-thi-them" class="col-span-1">Lần thi:</label>
                    <select id="input-lan-thi-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1">
                        <option value="1">Lần 1</option>
                        <option value="2">Lần 2</option>
                    </select>
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label for="input-mo-ta-bai-thi-them" class="col-span-1">Mô tả:</label>
                    <textarea id="input-mo-ta-bai-thi-them" class="input-them-bai-thi col-span-2 border rounded-sm px-2 py-1" style="height: 200px;"></textarea>
                </div>
                
            </div>
            <div class="flex justify-between mt-5">
                <button type="submit" class="mr-3 border border-cyan-400 py-2 px-4 rounded inline-flex items-center hover:bg-cyan-500 font-bold hover:text-white">
                    Thêm
                </button>
                <button id="btn-huy-them" type="button" class="mr-3 border border-rose-400 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#btn-huy-them').on('click', function(event){
        document.getElementById('modal-them-bai-thi').style.display = 'none';
        var inputList = document.querySelectorAll('.input-them-bai-thi')
        for (let i = 0; i < inputList.length; i++) {
            inputList[i].value = '';
        }
    });

    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }

    $('#form-them-bai-thi').on('submit', function(event){
        event.preventDefault();
        axios.post(secureUrl("{{ route('admin.quan-ly.bai-thi.handle-them-bai-thi') }}"), {
            ma_bai_thi: $('#input-ma-bai-thi-them').val(),
            ten_bai_thi: $('#input-ten-bai-thi-them').val(),
            mon_hoc: $('#input-mon-hoc-them').val(),
            thoi_gian_bat_dau: $('#input-thoi-gian-bat-dau-bai-thi-them').val(),
            thoi_gian_ket_thuc: $('#input-thoi-gian-ket-thuc-bai-thi-them').val(),
            mo_ta: $('#input-mo-ta-bai-thi-them').val(),
            lan_thi: $('#input-lan-thi-them').val(),
            ma_lop_hoc_phan: $('#input-ma-lop-hoc-phan-them').val(),
        })
        .then(function (response) {
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
            });
        })
        .catch(function (error) {
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        @if(Session::has('success_message'))
            Swal.fire({
                icon: 'success',
                title: '{{ Session::get('success_message') }}',
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    });
</script>
