<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-cap-nhat-khoa">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Chi tiết môn học
          </h4>
          <input type="hidden" id="data-id">
        </div>
        <form id="form-cap-nhat-khoa">
            <div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Mã khoa:</label>
                  <input id="input-ma-khoa-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-khoa bg-gray-300 opacity-50 cursor-not-allowed" type="text" readonly>
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Tên khoa:</label>
                  <input id="input-ten-khoa-cap-nhat" class="col-span-2 border rounded-sm px-2 py-1 input-cap-nhat-khoa" type="text">
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
        document.getElementById('modal-cap-nhat-khoa').style.display = 'none';
        var inputList = document.querySelectorAll('.input-cap-nhat-khoa')
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
  
    $('#form-cap-nhat-khoa').on('submit', function(event){
      // Ngăn chặn hành vi mặc định của form
      event.preventDefault();
      
      // Kiểm tra các trường nhập liệu
      var ma_khoa = $('#input-ma-khoa-cap-nhat').val();
      var ten_khoa = $('#input-ten-khoa-cap-nhat').val();
  
      if (ma_khoa && ten_khoa) {
        // Nếu tất cả các trường đã được nhập, gửi form đi
        axios.put(secureUrl("{{ route('admin.quan-ly.khoa.handle-cap-nhat-khoa') }}"), {
            id_khoa: $('#data-id').val(),
            ma_khoa: ma_khoa,
            ten_khoa: ten_khoa,
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
  