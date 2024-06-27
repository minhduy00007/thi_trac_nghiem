<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-cap-nhat-nguoi-dung">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Cập nhật người dùng
          </h4>
          <input type="hidden" id="data-id">
        </div>
        <form id="form-cap-nhat-nguoi-dung">
            <div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Họ tên:</label>
                    <input id="input-ten-nguoi-dung-cap-nhat" class="input-cap-nhat-nguoi-dung col-span-2 border rounded-sm px-2 py-1" type="text">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="">Email:</label>
                    <input id="input-email-nguoi-dung-cap-nhat" class="input-cap-nhat-nguoi-dung col-span-2 border rounded-sm px-2 py-1" type="text">
                </div>
                <div class="form-group grid grid-cols-3 gap-4 mb-2">
                    <label class="col-span-1" for="input-role-nguoi-dung-cap-nhat">Role:</label>
                    <select id="input-role-nguoi-dung-cap-nhat" class="input-cap-nhat-nguoi-dung col-span-2 border rounded-sm px-2 py-1">
                        <option value="Giảng viên">Giảng Viên</option>
                        <option value="Sinh viên">Sinh Viên</option>
                        <option value="Admin">Admin</option>
                    </select>
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
        document.getElementById('modal-cap-nhat-nguoi-dung').style.display = 'none';
        var inputList = document.querySelectorAll('.input-cap-nhat-nguoi-dung')
        for (let i = 0; i<inputList.length; i++) { 
            inputList[i].value = '';
        }
    })

    let urlcn = "{{ route('admin.quan-ly.nguoi-dung.handle-cap-nhat-nguoi-dung') }}";
    if (window.location.protocol === 'https:' && urlcn.startsWith('http:')) {
        urlcn = urlcn.replace('http:', 'https:');
    }

    $('#form-cap-nhat-nguoi-dung').on('submit', function(event){
      // Ngăn chặn hành vi mặc định của form
      event.preventDefault();
      
      // Kiểm tra các trường nhập liệu
      var ho_ten = $('#input-ten-nguoi-dung-cap-nhat').val();
      var email = $('#input-email-nguoi-dung-cap-nhat').val();
      var role = $('#input-role-nguoi-dung-cap-nhat').val();
  
      if (ho_ten && email && role) {
        // Nếu tất cả các trường đã được nhập, gửi form đi
        axios.put(urlcn, {
            id_nguoi_dung: $('#data-id').val(),
            ho_ten: ho_ten,
            email: email,
            role: role,
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