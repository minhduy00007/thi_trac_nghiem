<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-them-mon-hoc">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Thêm môn học
          </h4>
        </div>
        <form id="form-them-mon-hoc">
            <div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Mã môn học:</label>
                  <input id="input-ma-mon-hoc-them" class="input-them-mon-hoc col-span-2 border rounded-sm px-2 py-1" type="text">
              </div>
              <div class="form-group grid grid-cols-3 gap-4 mb-2">
                  <label class="col-span-1" for="">Tên môn học:</label>
                  <input id="input-ten-mon-hoc-them" class="input-them-mon-hoc col-span-2 border rounded-sm px-2 py-1" type="text">
              </div>
            </div>
            <div class="flex justify-between mt-5">
                <button type="submit" class="mr-3 border border-cyan-400 py-2 px-4 rounded inline-flex items-center hover:bg-cyan-500 font-bold hover:text-white">
                Thêm
              </button>
              <button id="btn-huy-them" type='button' class="mr-3 border border-rose-400 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                Hủy
              </button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
   $('#btn-huy-them').on('click', function(event){
        document.getElementById('modal-them-mon-hoc').style.display = 'none';
        var inputList = document.querySelectorAll('.input-them-mon-hoc')
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

    $('#form-them-mon-hoc').on('submit', function(event){
        event.preventDefault();
        axios.post(secureUrl("{{ route('admin.quan-ly.mon-hoc.handle-them-mon-hoc') }}"), {
            ma_mon_hoc: $('#input-ma-mon-hoc-them').val(),
            ten_mon_hoc: $('#input-ten-mon-hoc-them').val(),
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
    })

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