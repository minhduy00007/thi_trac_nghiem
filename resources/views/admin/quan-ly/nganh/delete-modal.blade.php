<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-xoa-nganh">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <input type="hidden" id="data-id">
        </div>
        <form id="form-xoa-nganh" class="p-6">
            <div class="flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-20 mb-4" viewBox="0 0 512 512">
                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" fill='#ef4444'/>
              </svg>
            </div>
            <div class="text-3xl text-red-400 flex items-center justify-center font-semibold">
              Bạn chắc chắn muốn xóa ngành này?
            </div>
            <div class="flex justify-end mt-8">
                <button onclick="xoa()" type="submit" class="mr-3 border border-emerald-400 py-2 px-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                Xác nhận
              </button>
              <button onclick="tatModal()" class="mr-3 border border-rose-400 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                Hủy
              </button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
  function tatModal() {
    document.getElementById('modal-xoa-nganh').style.display = 'none';
  }
  function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }

  function xoa() {
    $('#form-xoa-nganh').on('submit', function(event){
        event.preventDefault();
        axios.post(secureUrl("{{ route('admin.quan-ly.nganh.handle-xoa-nganh') }}"), {
            id_nganh: $('#data-id').val(),
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
                timer: 1000
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
  }
</script>