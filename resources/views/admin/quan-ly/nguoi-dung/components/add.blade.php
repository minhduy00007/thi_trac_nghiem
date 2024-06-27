<div class="flex items-center">
    <button class="relative inline-flex items-center justify-center overflow-hidden mb-2 me-2 " title="Tải file excel mẫu">
        <a id="download-link" class="inline-block"> 
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-10 h-10"> 
                <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM216 232V334.1l31-31c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-72 72c-9.4 9.4-24.6 9.4-33.9 0l-72-72c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l31 31V232c0-13.3 10.7-24 24-24s24 10.7 24 24z" fill="#008000"/> <!-- Thay đổi fill="#008000" để đổi màu thành xanh lá cây -->
            </svg>
        </a>
    </button>
    <button  onclick="showModalThem('{{ $modalThem }}')"  class=" mr-2 border border-cyan-400 py-2 px-4 rounded inline-flex items-center hover:bg-cyan-400 font-bold hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 fill-current transition hover:text-white" viewBox="0 0 640 512">
            <path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
        </svg>
        Thêm người dùng 
    </button>
    <div class="border border-green-400 py-2 px-4 rounded inline-flex items-center hover:bg-green-500 font-bold hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 fill-current transition hover:text-white" viewBox="0 0 640 512"><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
        <label for="file-upload">
            <input id="file-upload" type="file" accept=".xlsx, .xls" class="hidden" onchange="importData(event)">
            <span>Import File</span>
        </label>
    </div>
</div>
</div>
<script>
    function showModalThem(modalThem) {
            document.getElementById(modalThem).style.display = 'block';
            var button = event.target;
            var parentTr = button.closest('tr');
    }
    function importData(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || file.type === 'application/vnd.ms-excel') {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
                    // Kiểm tra cấu trúc dữ liệu
                    if (jsonData.length < 2 || jsonData[0].length !== 3) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Cấu trúc dữ liệu không đúng!',
                                text: 'Vui lòng kiểm tra lại cấu trúc dữ liệu trong file Excel.',
                                showConfirmButton: true,
                            });
                            return;
                    }

                    // Xử lý dữ liệu
                    const users = jsonData.slice(1).map(row => ({
                        ho_ten: row[0],
                        email: row[1],
                        role: row[2],
                    }));
                    // Gửi dữ liệu lên máy chủ
                    axios.post(secureUrl("{{ route('admin.quan-ly.nguoi-dung.handle-them-nguoi-dung') }}"), { data: users })
                        .then(function (response) {
                            if (response.data.success) {
                                window.location.replace(response.data.redirect);
                                return;
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
                };
                reader.readAsArrayBuffer(file);
            } else {
                alert('Please select a valid Excel file.');
            }
        }
    }

    function secureUrl(url) {
        if (window.location.protocol === 'https:' && url.startsWith('http:')) {
            return url.replace('http:', 'https:');
        }
        return url;
    }

    document.getElementById('download-link').addEventListener('click', function() {
        axios.get(secureUrl("{{ route('admin.quan-ly.nguoi-dung.download-template-nguoi-dung') }}"), { responseType: 'blob' })
            .then(function(response) {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'nguoi_dung_template.xlsx');
                document.body.appendChild(link);
                link.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(function(error) {
                console.error('Error downloading file:', error);
                // Xử lý lỗi nếu cần
            });
    });
</script>

