<aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white shadow z-50 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-auto border-r lg:shadow-none lg:w-auto transition-transform duration-300 ease-in-out">
    <div class="p-4 flex items-center justify-center mt-5">
        <h1 class="flex flex-col items-center text-2xl font-bold">
            <svg class="size-10 fill-current text-gray-600 mr-1 mb-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
            {{ $tenSinhVien }}
        </h1>
    </div>
    <nav class="p-2 mt-8">
        <ul class="space-y-2 font-medium">
            <li>
               <a href="{{ route('sinh-vien.quan-ly.dashboard.quan-ly-dashboard', ['id' => $id]) }}" class="flex items-center justify-center p-2 text-gray-900 rounded dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-600 transition duration-75 dark:text-white group-hover:text-black dark:group-hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"/></svg>
                  <span class="flex-1 ms-3 whitespace-nowrap text-gray-600 transition duration-75 group-hover:text-black dark:group-hover:text-black">Dashboard</span>
               </a>
            </li>
              <li>
                 <a href="{{ route('sinh-vien.quan-ly.xem-diem.xem-diem-sinh-vien', ['id' => $id]) }}" class="flex items-center justify-center p-2 text-gray-900 rounded dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                   <svg class="flex-shrink-0 w-5 h-5 text-gray-600 transition duration-75 dark:text-white group-hover:text-black dark:group-hover:text-black" aria-hidden="true" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                     <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap text-gray-600 transition duration-75 group-hover:text-black dark:group-hover:text-black">Xem điểm</span>              
                 </a>
              </li>
              <li>
                 <a href="{{ route('microsoft-logout')}}" class="flex items-center justify-center p-2 text-gray-900 rounded dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-600 transition duration-75 dark:text-white group-hover:text-black dark:group-hover:text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                       <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap text-gray-600 transition duration-75 group-hover:text-black dark:group-hover:text-black">Đăng xuất</span>
                 </a>
              </li>
           </ul>
    </nav>
</aside>
