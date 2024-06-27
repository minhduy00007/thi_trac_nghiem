<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-giang-vien">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-screen sm:max-w-screen-sm">
        <div class="modal-title mb-6">
          <input type="hidden" id="lop-hoc-phan-id--danh-sach-giang-vien">
          <input type="hidden" id="danh-sach-giang-vien-hien-tai-data--lop-hoc-phan">
        </div>
        <form id="form-danh-sach-giang-vien-lop-hoc-phan" class="p-6 mb-0">
            <h3>Danh sách giảng viên</h3>
            <div class="container mx-auto p-1 h-40 overflow-y-auto border grid grid-cols-2 gap-2 rounded" id='danh-sach-giang-vien--lop-hoc-phan--selected'>
                
            </div>
            <div class="mt-2">
                <span>
                    Tìm kiếm bằng mã giảng viên
                </span>
                <div class="w-full relative">
                    <input oninput="handleTimKiem()" type="text" class="w-full h-8 px-4 py-2 border outline-none rounded" id="input-search-giang-vien--lop-hoc-phan">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 absolute right-2 top-1/2 -translate-y-1/2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>  
                </div>
            </div>
            <div class="container mx-auto p-1 h-64 overflow-y-auto border grid grid-cols-1 rounded mt-1" id='danh-sach-giang-vien--lop-hoc-phan'>
                
            </div>
            <div class="flex justify-end mt-8">
                <button type="submit" class="mr-3 border-2 border-emerald-500 py-2 px-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                    Xác nhận
                </button>
                <button id="btn-huy-giang-vien" class=" border-2 border-rose-500 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>
@php
    $dsSinhVienAll = [];
    $dsGiangVienAll = [];
    $dsBaiThiAll = [];
    if ($dataType == 'lop_hoc_phan') {
        $dsSinhVienAll = $danhSachSinhVienAll;
        $dsGiangVienAll = $danhSachGiangVienAll;
        $dsBaiThiAll = $danhSachBaiThiAll;
    }
@endphp
<script type="text/javascript">
    var danhSachGiangVienAll = @json($dsGiangVienAll);
    
    function handleTimKiem() {
        var searchValue = document.querySelector("#input-search-giang-vien--lop-hoc-phan").value;

        var danhSachGiangVienHienTaiString = document.getElementById('danh-sach-giang-vien-hien-tai-data--lop-hoc-phan').value
        var danhSachGiangVienHienTai = JSON.parse(danhSachGiangVienHienTaiString.length > 0 ? danhSachGiangVienHienTaiString : '[]');
        danhSachGiangVienHienTai.map(item => {
            var index = danhSachGiangVienAll.findIndex(element => element.ma_giang_vien == item.ma_giang_vien)
            item.ten_giang_vien = danhSachGiangVienAll[index].ten_giang_vien
        })
        var danhSachGiangVienHienTaiBlock = document.getElementById('danh-sach-giang-vien--lop-hoc-phan--selected')
        var innerHTMl = ``;
        danhSachGiangVienHienTai.map(item => {
            innerHTMl += `
            <div class="border w-full h-fit flex items-center p-2 mb-1">
                <div class="w-5/6">
                    <p class="">
                        ${item.ten_giang_vien}
                    </p>
                    <p class="">
                        ${item.ma_giang_vien}
                    </p>
                </div>
                <div class="w-1/6">
                    <button onclick="xoaGiangVien('${item.ma_giang_vien}')"  type="button" class=" hover:border">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>`
        })
        danhSachGiangVienHienTaiBlock.innerHTML = innerHTMl;

        var danhSachGiangVienAllBlock = document.getElementById('danh-sach-giang-vien--lop-hoc-phan')
        var innerHTMl = ``;
        danhSachGiangVienAll.map(item => {
            if((item.ma_giang_vien).includes(searchValue)){
                if(danhSachGiangVienHienTai.findIndex(element => element.ma_giang_vien == item.ma_giang_vien) != -1) {
                    innerHTMl += `
                    <div class="border w-full h-fit flex items-center p-2 mb-1 bg-gray-200">
                        <div class="w-5/6">
                            <p class="">
                                ${item.ten_giang_vien}
                            </p>
                            <p class="">
                                ${item.ma_giang_vien}
                            </p>
                        </div>
                        <div class="w-1/6">
                            <button class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>`
                } else {
                    innerHTMl += `
                    <div class="border w-full h-fit flex items-center p-2 mb-1">
                        <div class="w-5/6">
                            <p class="">
                                ${item.ten_giang_vien}
                            </p>
                            <p class="">
                                ${item.ma_giang_vien}
                            </p>
                        </div>
                        <div class="w-1/6">
                            <button onclick="themGiangVien('${item.ma_giang_vien}')" type="button" class=" hover:border">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>`
                }
            }
        })
        danhSachGiangVienAllBlock.innerHTML = innerHTMl;    
    }

    function themGiangVien(maGiangVien){
        var searchValue = document.querySelector("#input-search-giang-vien--lop-hoc-phan").value;
        var danhSachGiangVienHienTaiString = document.getElementById('danh-sach-giang-vien-hien-tai-data--lop-hoc-phan').value
        var danhSachGiangVienHienTai = JSON.parse(danhSachGiangVienHienTaiString.length > 0 ? danhSachGiangVienHienTaiString : '[]');
        danhSachGiangVienHienTai.push({
            ma_giang_vien: maGiangVien,
        })
        document.getElementById('danh-sach-giang-vien-hien-tai-data--lop-hoc-phan').value = JSON.stringify(danhSachGiangVienHienTai);
        danhSachGiangVienHienTai.map(item => {
            var index = danhSachGiangVienAll.findIndex(element => element.ma_giang_vien == item.ma_giang_vien)
            item.ten_giang_vien = danhSachGiangVienAll[index].ten_giang_vien
        })

        var danhSachGiangVienHienTaiBlock = document.getElementById('danh-sach-giang-vien--lop-hoc-phan--selected')
        var innerHTMl = ``;
        danhSachGiangVienHienTai.map(item => {
            innerHTMl += `
            <div class="border w-full h-fit flex items-center p-2 mb-1">
                <div class="w-5/6">
                    <p class="">
                        ${item.ten_giang_vien}
                    </p>
                    <p class="">
                        ${item.ma_giang_vien}
                    </p>
                </div>
                <div class="w-1/6">
                    <button onclick="xoaGiangVien('${item.ma_giang_vien}')" type="button" class=" hover:border">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>`
        })
        danhSachGiangVienHienTaiBlock.innerHTML = innerHTMl;

        var danhSachGiangVienAllBlock = document.getElementById('danh-sach-giang-vien--lop-hoc-phan')
        var innerHTMl = ``;
        danhSachGiangVienAll.map(item => {
            if((item.ma_giang_vien).includes(searchValue)){
                if(danhSachGiangVienHienTai.findIndex(element => element.ma_giang_vien == item.ma_giang_vien) != -1) {
                    innerHTMl += `
                    <div class="border w-full h-fit flex items-center p-2 mb-1 bg-gray-200">
                        <div class="w-5/6">
                            <p class="">
                                ${item.ten_giang_vien}
                            </p>
                            <p class="">
                                ${item.ma_giang_vien}
                            </p>
                        </div>
                        <div class="w-1/6">
                            <button class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>`
                } else {
                    innerHTMl += `
                    <div class="border w-full h-fit flex items-center p-2 mb-1">
                        <div class="w-5/6">
                            <p class="">
                                ${item.ten_giang_vien}
                            </p>
                            <p class="">
                                ${item.ma_giang_vien}
                            </p>
                        </div>
                        <div class="w-1/6">
                            <button onclick="themGiangVien('${item.ma_giang_vien}')" type="button" class=" hover:border">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>`
                }
            }
        })
        danhSachGiangVienAllBlock.innerHTML = innerHTMl;
    }



    function xoaGiangVien(maGiangVien){
        var searchValue = document.querySelector("#input-search-giang-vien--lop-hoc-phan").value;
        var danhSachGiangVienHienTaiString = document.getElementById('danh-sach-giang-vien-hien-tai-data--lop-hoc-phan').value
        var danhSachGiangVienHienTai = JSON.parse(danhSachGiangVienHienTaiString.length > 0 ? danhSachGiangVienHienTaiString : '[]');
        danhSachGiangVienHienTai = danhSachGiangVienHienTai.filter(item => item.ma_giang_vien !== maGiangVien);
        document.getElementById('danh-sach-giang-vien-hien-tai-data--lop-hoc-phan').value = JSON.stringify(danhSachGiangVienHienTai);
        danhSachGiangVienHienTai.map(item => {
            var index = danhSachGiangVienAll.findIndex(element => element.ma_giang_vien == item.ma_giang_vien)
            item.ten_giang_vien = danhSachGiangVienAll[index].ten_giang_vien
        })

        var danhSachGiangVienHienTaiBlock = document.getElementById('danh-sach-giang-vien--lop-hoc-phan--selected')
        var innerHTMl = ``;
        danhSachGiangVienHienTai.map(item => {
            innerHTMl += `
            <div class="border w-full h-fit flex items-center p-2 mb-1">
                <div class="w-5/6">
                    <p class="">
                        ${item.ten_giang_vien}
                    </p>
                    <p class="">
                        ${item.ma_giang_vien}
                    </p>
                </div>
                <div class="w-1/6">
                    <button onclick="xoaGiangVien('${item.ma_giang_vien}')" type="button" class=" hover:border">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>`
        })
        danhSachGiangVienHienTaiBlock.innerHTML = innerHTMl;

        var danhSachGiangVienAllBlock = document.getElementById('danh-sach-giang-vien--lop-hoc-phan')
        var innerHTMl = ``;
        danhSachGiangVienAll.map(item => {
            if((item.ma_giang_vien).includes(searchValue)){
                if(danhSachGiangVienHienTai.findIndex(element => element.ma_giang_vien == item.ma_giang_vien) != -1) {
                    innerHTMl += `
                    <div class="border w-full h-fit flex items-center p-2 mb-1 bg-gray-200">
                        <div class="w-5/6">
                            <p class="">
                                ${item.ten_giang_vien}
                            </p>
                            <p class="">
                                ${item.ma_giang_vien}
                            </p>
                        </div>
                        <div class="w-1/6">
                            <button class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>`
                } else {
                    innerHTMl += `
                    <div class="border w-full h-fit flex items-center p-2 mb-1">
                        <div class="w-5/6">
                            <p class="">
                                ${item.ten_giang_vien}
                            </p>
                            <p class="">
                                ${item.ma_giang_vien}
                            </p>
                        </div>
                        <div class="w-1/6">
                            <button onclick="themGiangVien('${item.ma_giang_vien}')" type="button" class=" hover:border">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </button>
                        </div>
                    </div>`
                }
            }
        })
        danhSachGiangVienAllBlock.innerHTML = innerHTMl;
    }
    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }

    $('#form-danh-sach-giang-vien-lop-hoc-phan').on('submit', function(event){
        event.preventDefault();
        axios.put(secureUrl("{{ route('admin.quan-ly.lop-hoc-phan.handle-cap-nhat-danh-sach-giang-vien-lop-hoc-phan') }}"), {
            id: $('#lop-hoc-phan-id--danh-sach-giang-vien').val(),
            danh_sach_giang_vien: $('#danh-sach-giang-vien-hien-tai-data--lop-hoc-phan').val(),
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
    $('#btn-huy-giang-vien').on('click', function(event){
        event.preventDefault();
        document.getElementById('modal-giang-vien').style.display = 'none';
    })

</script>