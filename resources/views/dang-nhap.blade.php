<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Đăng nhập</title>
</head>
<body>
    <div class="w-screen h-screen relative" style="background-image: url('https://i.ibb.co/QjYMGcj/auth-bg.jpg');">   
        <div class=" bg-black bg-opacity-75 w-[200px] sm:w-[460px] h-screen absolute top-0 left-0 sm:block hidden">

        </div>
        <div class="w-full h-full xl:max-w-screen-xl flex justify-center">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="flex items-center col-span-1">
                    <div class="p-4 bg-white shadow-lg border w-[360px] rounded z-10">
                        <form id="form-dang-nhap">
                            <div class="flex justify-between items-end mb-8">
                                <h2 class="text-xl font-semibold">Đăng nhập</h2> 
                            </div>
                            <div class="form-group mt-4">
                                <input type="text" name="email-dang-nhap" id="email-dang-nhap" placeholder="Email đăng nhập" class="border outline-none rounded w-full py-1 px-2 h-10">
                            </div>
                            <div class="form-group mt-4">
                                <input type="password" name="mat-khau-dang-nhap" id="mat-khau-dang-nhap" placeholder="Mật khẩu" class="border outline-none rounded w-full py-1 px-2 h-10">
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="w-full rounded bg-blue-500 text-white font-bold py-2 border border-blue-500 hover:bg-white hover:text-blue-500">
                                    Đăng nhập
                                </button>
                            </div>
                        </form>
                        <div class="mt-4 w-full flex justify-center">
                            <a href="{{ route('microsoft-login') }}" class="w-full py-2 px-4 flex items-center justify-center gap-x-2 text-sm font-semibold rounded-lg border border-red-500 text-red-500 hover:border-red-400 hover:text-red-400 disabled:opacity-50 disabled:pointer-events-none">
                                <img src="https://i.ibb.co/tXWjSMF/logo-vlu.png" class="h-8 me-3" alt="Văn Lang Logo" />
                                Tài khoản Văn Lang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script type="text/javascript">
    let url = "{{ route('handle-dang-nhap') }}";
    if (window.location.protocol === 'https:' && url.startsWith('http:')) {
        url = url.replace('http:', 'https:');
    }

    $('#form-dang-nhap').on('submit', function(event){
        event.preventDefault();
        axios.post(url, {
            email: $('#email-dang-nhap').val(),
            matKhau: $('#mat-khau-dang-nhap').val(),
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
            });
        })
        .catch(function (error) {
            Swal.fire({
                icon: 'error',
                title: 'Có lỗi hệ thống! Xin lỗi bạn vì sự bất tiện này!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });
</script>
