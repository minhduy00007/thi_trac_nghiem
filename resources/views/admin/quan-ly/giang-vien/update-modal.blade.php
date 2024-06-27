<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-cap-nhat-giang-vien">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Chi tiết giảng viên
          </h4>
          <input type="hidden" id="data-id">
        </div>
        <form id="form-cap-nhat-giang-vien" class="mb-0">
            <div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Mã giảng viên:</label>
                  <input id="input-ma-giang-vien-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-giang-vien" type="text">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Tên giảng viên:</label>
                  <input id="input-ten-giang-vien-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-giang-vien" type="text">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Số điện thoại:</label>
                  <input id="input-so-dien-thoai-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-giang-vien" type="text">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Email:</label>
                  <input id="input-email-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-giang-vien" type="text">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Ngày sinh:</label>
                  <input id="input-ngay-sinh-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-giang-vien" type="date">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Khoa:</label>
                  <select name="" id="input-ma-khoa-cap-nhat" class="input-cap-nhat-giang-vien col-span-2 border rounded-sm px-2 py-1" >
                    @foreach ($danhSachKhoa as $khoa)
                        <option value="{{ $khoa->ma_khoa }}">{{ $khoa->ten_khoa }}</option>
                    @endforeach
                  </select>
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                <label class="col-span-1" for="">Ngành:</label>
                <select name="" id="input-ma-nganh-cap-nhat" class="input-cap-nhat-giang-vien col-span-2 border rounded-sm px-2 py-1" >
                    @foreach ($danhSachNganh as $nganh)
                        <option value="{{ $nganh->ma_nganh }}">{{ $nganh->ten_nganh }}</option>
                    @endforeach
                </select>
            </div>
            </div>
            <div class="flex justify-between mt-5">
                <button  type="submit" class="mr-3 border border-emerald-400 py-2 px-4 mt-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                Cập nhật
              </button>
              <button id="btn-huy-cap-nhat" type='button' class="mr-3 border border-rose-400 py-2 px-4 mt-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                Hủy
              </button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $('#btn-huy-cap-nhat').on('click', function(event){
        document.getElementById('modal-cap-nhat-giang-vien').style.display = 'none';
        var inputList = document.querySelectorAll('.input-cap-nhat-giang-vien')
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
  
    $('#form-cap-nhat-giang-vien').on('submit', function(event){
      // Ngăn chặn hành vi mặc định của form
      event.preventDefault();
      
      // Kiểm tra các trường nhập liệu
      var ma_giang_vien = $('#input-ma-giang-vien-cap-nhat').val();
      var ten_giang_vien = $('#input-ten-giang-vien-cap-nhat').val();
      var so_dien_thoai = $('#input-so-dien-thoai-cap-nhat').val();
      var email = $('#input-email-cap-nhat').val();
      var ngay_sinh = $('#input-ngay-sinh-cap-nhat').val();
      var ma_khoa = $('#input-ma-khoa-cap-nhat').val();
      var ma_nganh = $('#input-ma-nganh-cap-nhat').val();
      if (ma_giang_vien && ten_giang_vien && so_dien_thoai && email && ngay_sinh && ma_khoa && ma_nganh) {
        // Nếu tất cả các trường đã được nhập, gửi form đi
        axios.put(secureUrl("{{ route('admin.quan-ly.giang-vien.handle-cap-nhat-giang-vien') }}"), {
            id_giang_vien: $('#data-id').val(),
            ma_giang_vien: ma_giang_vien,
            ten_giang_vien: ten_giang_vien,
            so_dien_thoai: so_dien_thoai,
            email: email,
            ngay_sinh: ngay_sinh,
            ma_khoa: ma_khoa,
            ma_nganh: ma_nganh,
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
        // Nếu có trường chưa được nhập, hiển thị thông báo lỗi
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
  