<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen p-2" style="display: none; z-index:100;" id="modal-cac-mon-giang-day">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="modal-title mb-6">
          <h4 class="text-lg font-semibold">
              Các môn giảng dạy
          </h4>
        </div>
        <form id="form-cac-mon-giang-day" class="mb-0">
            <input type="hidden" name="" id="giang-vien-id--cac-mon-giang-day">
            <div id="list-mon-hoc">
                <div class="list-group-item flex justify-between items-center">
                    <div class="">
                        <select name="" id="" class="select-mon-hoc col-span-2 border rounded-sm px-2 py-1 w-[300px]">
                            @foreach ($danhSachMon as $monHoc)
                                <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ml-4">
                        <button onclick="xoaMon()" class="" type="button" title="Xóa">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 448 512">
                                <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z" fill="#fb7185"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" onclick="themMon()" class="px-2 border border-blue-300 rounded">
                    +
                </button>
            </div>
             <div class="flex justify-between mt-5">
                <button onclick="xacNhan()" type="submit" class="mr-3 border border-cyan-400 py-2 px-4 mt-4 rounded inline-flex items-center hover:bg-cyan-500 font-bold hover:text-white">
                Xác nhận
              </button>
              <button onclick="tatModal()" type='button' class="mr-3 border border-rose-400 py-2 px-4 mt-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                Hủy
              </button>
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    function tatModal() {
        document.getElementById('modal-cac-mon-giang-day').style.display = 'none';
    }
    function themMon() {
        var danhSachMon = @json($danhSachMon);
        var parentElement = document.getElementById("list-mon-hoc")
        var newDiv = document.createElement('div');
        newDiv.classList.add('list-group-item', 'flex', 'justify-between', 'items-center', 'mt-3');
        newDiv.innerHTML = `
                    <div class="">
                        <select name="" id="" class="select-mon-hoc col-span-2 border rounded-sm px-2 py-1 w-[300px]">
                            ${
                                danhSachMon.map((mon, index)=>{
                                    return `<option value="${mon.ma_mon_hoc}">${mon.ten_mon_hoc}</option>`
                                })
                            }
                        </select>
                    </div>
                    <div class="ml-4">
                        <button onclick="xoaMon()" class="" type="button" title="Xóa">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 448 512">
                                <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z" fill="#fb7185"/>
                            </svg>
                        </button>
                    </div>`
        parentElement.appendChild(newDiv)
    }

    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }
    function xacNhan() {
        var parentElement = document.getElementById("list-mon-hoc")
        var childrenElements = parentElement.children
        var listMonHoc = []
        for (let i = 0; i < childrenElements.length; i++) {
            var maMon = childrenElements[i].querySelector('.select-mon-hoc').value
            if(maMon){
                listMonHoc.push(
                {
                    thu_tu: i+1,
                    ma_mon: maMon,
                })
            }
        }
        var jsonCacMonGiangDay = JSON.stringify(listMonHoc)
        event.preventDefault();
        axios.post(secureUrl("{{ route('admin.quan-ly.giang-vien.handle-cac-mon-giang-day') }}"), {
            data: jsonCacMonGiangDay,
            giangVienId: $('#giang-vien-id--cac-mon-giang-day').val(),
        })
        .then(function (response) {
            if (response.data.success) {
                window.location.replace(response.data.redirect);
                return;
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
    }

    function xoaMon() {
        var button = event.target;
        var parentItem = button.closest('.list-group-item');
        parentItem.remove();
    }
</script>