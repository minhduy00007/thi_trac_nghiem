<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-cap-nhat-lop-hoc-phan">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Chi tiết lớp học phần
          </h4>
          <input type="hidden" id="data-id">
        </div>
        <form id="form-cap-nhat-lop-hoc-phan">
            <div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Mã lớp học phần:</label>
                  <input id="input-ma-lop-hoc-phan-cap-nhat" class="input-cap-nhat-lop-hoc-phan col-span-2 border rounded-sm px-2 py-1 bg-gray-300 opacity-50 cursor-not-allowed" type="text" readonly>
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Tên lớp học phần:</label>
                  <input id="input-ten-lop-hoc-phan-cap-nhat" class="input-cap-nhat-lop-hoc-phan col-span-2 border rounded-sm px-2 py-1" type="text">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                <label class="col-span-1" for="">Môn học:</label>
                <select name="" id="input-mon-hoc-lop-hoc-phan-cap-nhat" class="input-cap-nhat-lop-hoc-phan col-span-2 border rounded-sm px-2 py-1" >
                    @foreach ($danhSachMon as $mon_hoc)
                        <option value="{{ $mon_hoc->ma_mon_hoc }}">{{ $mon_hoc->ten_mon_hoc }}</option>
                    @endforeach
                  </select>
            </div>
            <div class="form-group grid grid-cols-3 gap-4 mb-2">
                <label class="col-span-1" for="">Thời gian bắt đầu:</label>
                <input id="input-bat-dau-lop-hoc-phan-cap-nhat" class="input-cap-nhat-lop-hoc-phan col-span-2 border rounded-sm px-2 py-1" type="datetime-local" placeholder="Chọn thời gian bắt đầu">
            </div>
            <div class="form-group grid grid-cols-3 gap-4 mb-2">
                <label class="col-span-1" for="">Thời gian kết thúc:</label>
                <input id="input-ket-thuc-lop-hoc-phan-cap-nhat" class="input-cap-nhat-lop-hoc-phan col-span-2 border rounded-sm px-2 py-1" type="datetime-local" placeholder="Chọn thời gian kết thúc">
            </div>
            </div>
            <div class="flex justify-between mt-5">
                <button  type="submit" class="mr-3 border border-emerald-400 py-2 px-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                    Cập nhật
                </button>
              <button id="btn-huy-cap-nhat" type='button' class="mr-3 border-2 border-rose-500 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                Hủy
              </button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#btn-huy-cap-nhat').on('click', function(event){
        event.preventDefault();
        document.getElementById('modal-cap-nhat-lop-hoc-phan').style.display = 'none';
        var inputList = document.querySelectorAll('.input-cap-nhat-lop-hoc-phan')
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
  
    $('#form-cap-nhat-lop-hoc-phan').on('submit', function(event){
      // Ngăn chặn hành vi mặc định của form
      event.preventDefault();
      
      // Kiểm tra các trường nhập liệu
      var ma_lop_hoc_phan = $('#input-ma-lop-hoc-phan-cap-nhat').val();
      var ten_lop_hoc_phan = $('#input-ten-lop-hoc-phan-cap-nhat').val();
      var ma_mon_hoc = $('#input-mon-hoc-lop-hoc-phan-cap-nhat').val();
      var thoi_gian_bat_dau = $('#input-bat-dau-lop-hoc-phan-cap-nhat').val();
      var thoi_gian_ket_thuc = $('#input-ket-thuc-lop-hoc-phan-cap-nhat').val();
  
      if (ma_lop_hoc_phan && ten_lop_hoc_phan && ma_mon_hoc && thoi_gian_bat_dau && thoi_gian_ket_thuc ) {
        // Nếu tất cả các trường đã được nhập, gửi form đi
        axios.put(secureUrl("{{ route('admin.quan-ly.lop-hoc-phan.handle-cap-nhat-lop-hoc-phan') }}"), {
            id_lop_hoc_phan: $('#data-id').val(),
            ma_lop_hoc_phan: ma_lop_hoc_phan,
            ten_lop_hoc_phan: ten_lop_hoc_phan,
            ma_mon_hoc: ma_mon_hoc,
            thoi_gian_bat_dau: thoi_gian_bat_dau,
            thoi_gian_ket_thuc: thoi_gian_ket_thuc,
            
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
  