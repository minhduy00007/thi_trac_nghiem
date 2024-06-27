<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-cap-nhat-bai-thi">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Chi tiết bài thi
          </h4>
          <input type="hidden" id="data-id">
        </div>
        <form id="form-cap-nhat-bai-thi">
            <div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Mã bài thi:</label>
                    <input id="input-ma-bai-thi-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-bai-thi bg-gray-300 opacity-50 cursor-not-allowed" type="text" readonly >
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Tên bài thi:</label>
                    <input id="input-ten-bai-thi-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-bai-thi" type="text">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Môn học:</label>
                    <select id="input-mon-hoc-cap-nhat" class="input-cap-nhat-bai-thi col-span-2 border rounded-sm px-2 py-1 overflow-y-auto max-h-40">
                        @foreach ($danhSachMon as $mon)
                            <option value="{{ $mon->ma_mon_hoc }}">{{ $mon->ten_mon_hoc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Thời gian bắt đầu:</label>
                    <input id="input-thoi-gian-bat-dau-bai-thi-cap-nhat" class="input-cap-nhat-bai-thi col-span-2 border rounded-sm px-2 py-1" type="datetime-local" placeholder="Chọn thời gian bắt đầu">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Thời gian kết thúc:</label>
                    <input id="input-thoi-gian-ket-thuc-bai-thi-cap-nhat" class="input-cap-nhat-bai-thi col-span-2 border rounded-sm px-2 py-1" type="datetime-local" placeholder="Chọn thời gian kết thúc">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label for="input-lan-thi-cap-nhat" class="col-span-1">Lần thi:</label>
                    <select id="input-lan-thi-cap-nhat" class="input-cap-nhat-bai-thi col-span-2 border rounded-sm px-2 py-1">
                        <option value="1">Lần 1</option>
                        <option value="2">Lần 2</option>
                    </select>
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label for="input-mo-ta-bai-thi-cap-nhat" class="col-span-1">Mô tả:</label>
                    <textarea id="input-mo-ta-bai-thi-cap-nhat" class=" input-cap-nhat-bai-thi col-span-2 border rounded-sm px-2 py-1"  style="height: 200px;"></textarea>
                </div>
            </div>
            <div class="flex justify-between mt-5">
                <button  type="submit" class="mr-3 border border-emerald-400 py-2 px-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                Cập nhật
              </button>
              <button id="btn-huy-cap-nhat" type='button' class="mr-3 border border-rose-400 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                Hủy
              </button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#btn-huy-cap-nhat').on('click', function(event){
        document.getElementById('modal-cap-nhat-bai-thi').style.display = 'none';
        var inputList = document.querySelectorAll('.input-cap-nhat-bai-thi')
        for (let i = 0; i<inputList.length; i++) { 
            inputList[i].value = '';
        }
    })
    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }
  
    $('#form-cap-nhat-bai-thi').on('submit', function(event){
      // Ngăn chặn hành vi mặc định của form
      event.preventDefault();
      
      // Kiểm tra các trường nhập liệu
      var ma_bai_thi = $('#input-ma-bai-thi-cap-nhat').val();
      var ten_bai_thi = $('#input-ten-bai-thi-cap-nhat').val();
      var mon_hoc = $('#input-mon-hoc-cap-nhat').val();
      var thoi_gian_bat_dau = $('#input-thoi-gian-bat-dau-bai-thi-cap-nhat').val();
      var thoi_gian_ket_thuc = $('#input-thoi-gian-ket-thuc-bai-thi-cap-nhat').val();
      var  mo_ta = $('#input-mo-ta-bai-thi-cap-nhat').val();
      var lan_thi = $('#input-lan-thi-cap-nhat').val();
      var id_giang_vien = {{ $id }};
      var ma_lop_hoc_phan = $('#input-lop-hoc-phan-cap-nhat').val();
      if (ma_bai_thi && ten_bai_thi) {
        // Nếu tất cả các trường đã được nhập, gửi form đi
        axios.put(secureUrl("{{ route('giang-vien.quan-ly.bai-thi.handle-cap-nhat-bai-thi') }}"), {
            id_bai_thi: $('#data-id').val(),
            ma_bai_thi: ma_bai_thi,
            ten_bai_thi: ten_bai_thi,
            mon_hoc: mon_hoc,
            thoi_gian_bat_dau: thoi_gian_bat_dau,
            thoi_gian_ket_thuc: thoi_gian_ket_thuc,
            lan_thi: lan_thi,
            mo_ta: mo_ta,
            id_giang_vien: id_giang_vien,
            ma_lop_hoc_phan: ma_lop_hoc_phan,
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
            })
        })
        .catch(function (error) {
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                showConfirmButton: false,
                timer: 1500
            })
        });
      } else {
        Swal.fire({
            icon: 'error',
            title: 'Vui lòng nhập đầy đủ thông tin.',
            showConfirmButton: false,
            timer: 1500
        });
      }
    });
    document.addEventListener("DOMContentLoaded", function() {
        // Kiểm tra Session để hiển thị thông báo
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
  