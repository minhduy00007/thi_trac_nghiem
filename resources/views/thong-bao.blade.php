<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-md w-full text-center">
        <div class="mb-4">
            <img src="{{ asset('images/logo_vlu.png') }}" alt="Logo Trường Đại học Văn Lang" class="mx-auto w-24 h-24">
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Thông Báo</h1>
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p>{{ $message }}</p>
        </div>
        <a href="{{ route('dang-nhap') }}"
           class="inline-block bg-red-500 text-white px-4 py-2 rounded">
            Trở lại trang Đăng nhập
        </a>
        @if (isset($showLogout) && $showLogout)
            <a href="{{ route('microsoft-logout') }}"
               class="inline-block bg-yellow-500 text-white px-4 py-2 rounded mt-2">
                Đăng xuất tài khoản trước đó
            </a>
        @endif
    </div>
</body>
</html>