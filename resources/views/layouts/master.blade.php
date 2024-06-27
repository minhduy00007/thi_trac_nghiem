<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.css" integrity="sha512-Dovl+OCTZYdn+CwnU7ChS28VCZ1lDlhpZUpDIFvYtW8y20+lcZeWYnQrILYfGhcXSgzeXVhgjwQP39zfbdDPQw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('page-css')
    <title>@yield('title')</title>
</head>
<body>
    @include('layouts.sidebar')
    <div id="main-content" style="margin-top:80px; margin-left:255px;">
        <div class="container-fluid">
            <h2 class="text-lg font-bold text-gray-800 ml-2 mb-2">
                @yield('page-title')
            </h2>
            @yield('content')
        </div>
    </div>
</body>
</html>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js" integrity="sha512-o36qZrjup13zLM13tqxvZTaXMXs+5i4TL5UWaDCsmbp5qUcijtdCFuW9a/3qnHGfWzFHBAln8ODjf7AnUNebVg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function adjustMargin() {
        var screenWidth = window.innerWidth;
        var mainContent = document.getElementById('main-content');
        
        if (screenWidth <= 640) {
            mainContent.style.marginLeft = '0';
        } else {
            mainContent.style.marginLeft = '255px';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        adjustMargin();
        window.addEventListener('resize', adjustMargin);
    });
</script>

@yield('page-js')
