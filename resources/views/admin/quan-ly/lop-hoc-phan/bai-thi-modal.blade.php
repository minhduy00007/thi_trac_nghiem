<div class="fixed top-0 left-0 inset-0 overflow-y-auto bg-gray-600 bg-opacity-50 w-screen h-screen" style="display: none; z-index:100;" id="modal-bai-thi">
    <div class="bg-white rounded p-4 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-screen sm:max-w-screen-sm">
        <div class="modal-title mb-6">
          <input type="hidden" id="lop-hoc-phan-id--danh-sach-bai-thi">
          <input type="hidden" id="danh-sach-bai-thi-hien-tai-data--lop-hoc-phan">
          <input type="hidden" id="ma_mon_hoc--lop-hoc-phan">
        </div>
        <form id="form-danh-sach-bai-thi-lop-hoc-phan" class="p-6 mb-0">
            <h3>Danh sách bài thi</h3>
            <div class="container mx-auto p-1 h-40 overflow-y-auto border grid grid-cols-2 gap-2 rounded" id='danh-sach-bai-thi--lop-hoc-phan--selected'>
                
            </div>
            <div class="mt-2">
                <span>
                    Tìm kiếm bằng mã bài thi
                </span>
                <div class="w-full relative">
                    <input oninput="handleTimKiem()" type="text" class="w-full h-8 px-4 py-2 border outline-none rounded" id="input-search-bai-thi--lop-hoc-phan">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 absolute right-2 top-1/2 -translate-y-1/2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>  
                </div>
            </div>
            <div class="container mx-auto p-1 h-64 overflow-y-auto border grid grid-cols-1 rounded mt-1" id='danh-sach-bai-thi--lop-hoc-phan'>
                
            </div>
            <div class="flex justify-end mt-8">
                <button type="submit" class="mr-3 border border-emerald-500 py-2 px-4 rounded inline-flex items-center hover:bg-emerald-500 font-bold hover:text-white">
                    Xác nhận
                </button>
                <button id="btn-huy-bai-thi"  class=" border border-rose-500 py-2 px-4 rounded inline-flex items-center hover:bg-rose-500 font-bold hover:text-white">
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
    var danhSachBaiThiAll = @json($dsBaiThiAll);
    
    function handleTimKiem() {
        var searchValue = document.querySelector("#input-search-bai-thi--lop-hoc-phan").value;
        var maMonHocLopHocPhan = document.getElementById('ma_mon_hoc--lop-hoc-phan').value;
        var danhSachBaiThiHienTaiString = document.getElementById('danh-sach-bai-thi-hien-tai-data--lop-hoc-phan').value
        var danhSachBaiThiHienTai = JSON.parse(danhSachBaiThiHienTaiString.length > 0 ? danhSachBaiThiHienTaiString : '[]');
        danhSachBaiThiHienTai.map(item => {
            var index = danhSachBaiThiAll.findIndex(element => element.ma_bai_thi == item.ma_bai_thi)
            item.ten_bai_thi = danhSachBaiThiAll[index].ten_bai_thi
        })
        var danhSachBaiThiHienTaiBlock = document.getElementById('danh-sach-bai-thi--lop-hoc-phan--selected')
        var innerHTMl = ``;
        danhSachBaiThiHienTai.map(item => {
            innerHTMl += `
            <div class="border w-full h-fit flex items-center p-2 mb-1">
                <div class="w-5/6">
                    <p class="">
                            Tên bài thi: ${item.ten_bai_thi}
                            </p>
                            <p class="">
                                Mã bài thi: ${item.ma_bai_thi}
                            </p>
                            <p class="">
                                Lần thi: ${item.lan_thi}
                            </p>
                </div>
                <div class="w-1/6">
                    <button onclick="xoaBaiThi('${item.ma_bai_thi}', ${item.lan_thi})"  type="button" class=" hover:border">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>`
        })
        danhSachBaiThiHienTaiBlock.innerHTML = innerHTMl;

        var danhSachBaiThiAllBlock = document.getElementById('danh-sach-bai-thi--lop-hoc-phan')
        var innerHTMl = ``;
        danhSachBaiThiAll.map(item => {
            if (item.mon_hoc === maMonHocLopHocPhan) {
                if((item.ma_bai_thi).includes(searchValue)){
                    if(danhSachBaiThiHienTai.findIndex(element => element.ma_bai_thi == item.ma_bai_thi && element.lan_thi == item.lan_thi) != -1) {
                        innerHTMl += `
                        <div class="border w-full h-fit flex items-center p-2 mb-1 bg-gray-200">
                            <div class="w-5/6">
                                <p class="">
                                Tên bài thi: ${item.ten_bai_thi}
                                </p>
                                <p class="">
                                    Mã bài thi: ${item.ma_bai_thi}
                                </p>
                                <p class="">
                                    Lần thi: ${item.lan_thi}
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
                                Tên bài thi: ${item.ten_bai_thi}
                                </p>
                                <p class="">
                                    Mã bài thi: ${item.ma_bai_thi}
                                </p>
                                <p class="">
                                    Lần thi: ${item.lan_thi}
                                </p>
                            </div>
                            <div class="w-1/6">
                                <button onclick="themBaiThi('${item.ma_bai_thi}')" type="button" class=" hover:border">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>`
                    }
                }
            }
        })
        danhSachBaiThiAllBlock.innerHTML = innerHTMl;    
    }

    function themBaiThi(maBaiThi, lanThi) {
        var searchValue = document.querySelector("#input-search-bai-thi--lop-hoc-phan").value;
        var danhSachBaiThiHienTaiString = document.getElementById('danh-sach-bai-thi-hien-tai-data--lop-hoc-phan').value;
        var maMonHocLopHocPhan = document.getElementById('ma_mon_hoc--lop-hoc-phan').value;
        var danhSachBaiThiHienTai = JSON.parse(danhSachBaiThiHienTaiString.length > 0 ? danhSachBaiThiHienTaiString : '[]');
        
        // Thêm thông tin về lan_thi cho bài thi được thêm vào danh sách
        danhSachBaiThiHienTai.push({
            ma_bai_thi: maBaiThi,
            lan_thi: lanThi // Thêm thông tin về lan_thi
        });

        document.getElementById('danh-sach-bai-thi-hien-tai-data--lop-hoc-phan').value = JSON.stringify(danhSachBaiThiHienTai);
        danhSachBaiThiHienTai.map(item => {
            var index = danhSachBaiThiAll.findIndex(element => element.ma_bai_thi == item.ma_bai_thi)
            item.ten_bai_thi = danhSachBaiThiAll[index].ten_bai_thi
        })

        var danhSachBaiThiHienTaiBlock = document.getElementById('danh-sach-bai-thi--lop-hoc-phan--selected')
        var innerHTMl = ``;
        danhSachBaiThiHienTai.map(item => {
            innerHTMl += `
            <div class="border w-full h-fit flex items-center p-2 mb-1">
                <div class="w-5/6">
                    <p class="">
                    Tên bài thi: ${item.ten_bai_thi}
                    </p>
                    <p class="">
                        Mã bài thi: ${item.ma_bai_thi}
                    </p>
                    <p class="">
                        Lần thi: ${item.lan_thi}
                    </p>
                </div>
                <div class="w-1/6">
                    <button onclick="xoaBaiThi('${item.ma_bai_thi}', ${item.lan_thi})" type="button" class=" hover:border">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>`
        })
        danhSachBaiThiHienTaiBlock.innerHTML = innerHTMl;

        var danhSachBaiThiAllBlock = document.getElementById('danh-sach-bai-thi--lop-hoc-phan')
        var innerHTMl = ``;
        danhSachBaiThiAll.map(item => {
            if (item.mon_hoc === maMonHocLopHocPhan) {

                if ((item.ma_bai_thi).includes(searchValue)) {
                    if (danhSachBaiThiHienTai.findIndex(element => element.ma_bai_thi == item.ma_bai_thi && element.lan_thi == item.lan_thi) != -1) {
                        innerHTMl += `
                        <div class="border w-full h-fit flex items-center p-2 mb-1 bg-gray-200">
                            <div class="w-5/6">
                                <p class="">
                                Tên bài thi: ${item.ten_bai_thi}
                                </p>
                                <p class="">
                                    Mã bài thi: ${item.ma_bai_thi}
                                </p>
                                <p class="">
                                    Lần thi: ${item.lan_thi}
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
                                Tên bài thi: ${item.ten_bai_thi}
                                </p>
                                <p class="">
                                    Mã bài thi: ${item.ma_bai_thi}
                                </p>
                                <p class="">
                                    Lần thi: ${item.lan_thi}
                                </p>
                            </div>
                            <div class="w-1/6">
                                <button onclick="themBaiThi('${item.ma_bai_thi}', ${item.lan_thi})" type="button" class=" hover:border">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>`
                    }
                }
            }
        })
        danhSachBaiThiAllBlock.innerHTML = innerHTMl;
    }




    function xoaBaiThi(maBaiThi,lanThi){
        var searchValue = document.querySelector("#input-search-bai-thi--lop-hoc-phan").value;
        var danhSachBaiThiHienTaiString = document.getElementById('danh-sach-bai-thi-hien-tai-data--lop-hoc-phan').value
        var danhSachBaiThiHienTai = JSON.parse(danhSachBaiThiHienTaiString.length > 0 ? danhSachBaiThiHienTaiString : '[]');
        danhSachBaiThiHienTai = danhSachBaiThiHienTai.filter(item => item.ma_bai_thi !== maBaiThi || item.lan_thi !== lanThi);
        document.getElementById('danh-sach-bai-thi-hien-tai-data--lop-hoc-phan').value = JSON.stringify(danhSachBaiThiHienTai);
        var maMonHocLopHocPhan = document.getElementById('ma_mon_hoc--lop-hoc-phan').value;
        danhSachBaiThiHienTai.map(item => {
            var index = danhSachBaiThiAll.findIndex(element => element.ma_bai_thi == item.ma_bai_thi)
            item.ten_bai_thi = danhSachBaiThiAll[index].ten_bai_thi
        })

        var danhSachBaiThiHienTaiBlock = document.getElementById('danh-sach-bai-thi--lop-hoc-phan--selected')
        var innerHTMl = ``;
        danhSachBaiThiHienTai.map(item => {
            innerHTMl += `
            <div class="border w-full h-fit flex items-center p-2 mb-1">
                <div class="w-5/6">
                    <p class="">
                       Tên bài thi: ${item.ten_bai_thi}
                    </p>
                    <p class="">
                        Mã bài thi: ${item.ma_bai_thi}
                    </p>
                    <p class="">
                        Lần thi: ${item.lan_thi}
                    </p>
                </div>
                <div class="w-1/6">
                    <button onclick="xoaBaiThi('${item.ma_bai_thi}', ${item.lan_thi})" type="button" class=" hover:border">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>`
        })
        danhSachBaiThiHienTaiBlock.innerHTML = innerHTMl;

        var danhSachBaiThiAllBlock = document.getElementById('danh-sach-bai-thi--lop-hoc-phan')
        var innerHTMl = ``;
        danhSachBaiThiAll.map(item => {
            if (item.mon_hoc === maMonHocLopHocPhan) {
                if((item.ma_bai_thi).includes(searchValue)){
                    if(danhSachBaiThiHienTai.findIndex(element => element.ma_bai_thi == item.ma_bai_thi && element.lan_thi == item.lan_thi) != -1) {
                        innerHTMl += `
                        <div class="border w-full h-fit flex items-center p-2 mb-1 bg-gray-200">
                            <div class="w-5/6">
                                <p class="">
                                Tên bài thi: ${item.ten_bai_thi}
                                </p>
                                <p class="">
                                    Mã bài thi: ${item.ma_bai_thi}
                                </p>
                                <p class="">
                                    Lần thi: ${item.lan_thi}
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
                                Tên bài thi: ${item.ten_bai_thi}
                                </p>
                                <p class="">
                                    Mã bài thi: ${item.ma_bai_thi}
                                </p>
                                <p class="">
                                    Lần thi: ${item.lan_thi}
                                </p>
                            </div>
                            <div class="w-1/6">
                                <button onclick="themBaiThi('${item.ma_bai_thi}', ${item.lan_thi})" type="button" class=" hover:border">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>`
                    }
                }
            }
        })
        danhSachBaiThiAllBlock.innerHTML = innerHTMl;
    }
    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }
    $('#btn-huy-bai-thi').on('click', function(event){
        event.preventDefault(); // Ngăn chặn hành động mặc định của form
        document.getElementById('modal-bai-thi').style.display = 'none';
    });
    $('#form-danh-sach-bai-thi-lop-hoc-phan').on('submit', function(event){
        event.preventDefault();
        axios.put(secureUrl("{{ route('admin.quan-ly.lop-hoc-phan.handle-cap-nhat-danh-sach-bai-thi-lop-hoc-phan') }}"), {
            id: $('#lop-hoc-phan-id--danh-sach-bai-thi').val(),
            danh_sach_bai_thi: $('#danh-sach-bai-thi-hien-tai-data--lop-hoc-phan').val(),
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
    

</script>