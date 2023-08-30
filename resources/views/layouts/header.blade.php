<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ config('app.name', 'SIReKa') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
		<link rel="icon" href="/pictures/itslogo.png">
        <!-- Styles -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/redmond/jquery-ui.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    	<script defer src="/js/script.js"></script>
        @vite('resources/css/app.css')
        <style>
			.active {
				color: #d2e80c;
				border-bottom: 2px solid;
				border-image-slice: 1;
				border-image-source: linear-gradient(to right, #d2e80c, #008FFF);
			}
        </style>
</head>
<body class="bg-white-800 font-sans leading-normal tracking-normal">

	<nav class="flex items-center justify-between flex-wrap bg-blue-900 p-2 w-full z-20 top-0 sticky tx-roboto">
		<div class="flex items-center flex-shrink-0 text-white mr-6">
		<div>
			<img class="h-20 w-20" src="/pictures/itslogo.png" alt="Logo ITS">
		</div>
		<div>
			<a class="text-white no-underline hover:text-white hover:no-underline" href="#">
				<span class="text-xl pl-2"><i class="em em-grinning"></i>SIReKa: SIM Reservasi Kelas</span>
			</a>
		</div>
	</div>

		<div class="block lg:hidden">
			<button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-white border-white hover:border-blue">
				<svg class="fill-white h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
			</button>
		</div>

		<div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block pt-6 justify-items-center lg:pt-0" id="nav-content">
			<ul class="list-reset lg:flex lg:text-center justify-end flex-1 items-center">
				<li class="mr-3">
					<a class="inline-block text-white no-underline hover:text-yellow-200 hover:text-underline py-1 px-4 {{ Request::is('/', '||', 'ruangan', '||', 'lantai4', '||', 'lantai5', '||', 'lantai6', '||', 'lantai7', '||', 'lantai8') ? 'active': '';}}" href="{{route('home')}}">Home</a>
				</li>
				<li class="mr-3">
					<a class="inline-block text-white no-underline hover:text-yellow-200 hover:text-underline py-1 px-4 {{ Request::is('reservasi','reservasi/InformasiPJ','reservasi/detailPeminjaman','reservasi/detailKegiatan') ? 'active': '';}}" href="{{route('reservasi')}}">Reservasi</a>
				</li>
				<li class="mr-3">
					<a class="inline-block text-white no-underline hover:text-yellow-200 hover:text-underline py-1 px-4 {{ Request::is('jadwal') ? 'active': '';}}" href="{{ route('jadwal') }}">Jadwal</a>
				</li>
				<li class="mr-3">
					<a class="inline-block text-white no-underline hover:text-yellow-200 hover:text-underline py-1 px-4 {{ Request::is('status') ? 'active': '';}}" href="{{ route('status') }}">Status</a>
				</li>
				<li class="mr-3">
					<a class="inline-block text-white no-underline hover:text-yellow-200 hover:text-underline py-1 px-4 {{ Request::is('panduan') ? 'active': '';}}" href="{{route('panduanReservasi')}}">Panduan</a>
				</li>
				<li class="mr-3">
					<a class="inline-block text-white no-underline hover:text-gray-200 hover:text-underline py-2 px-4 {{ Request::is('staffdisplay') ? 'active': '';}}" href="{{ route('staffDisplay') }}">Staff</a>
				</li>
				<li class="mr-3">
				<button type="button" class="inline-block text-white no-underline hover:text-gray-200 hover:text-underline py-2 px-4  bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">MyITS SSO</button>
				</li>
			</ul>
		</div>
	</nav>

    <!--Container-->
	<div class="mx-auto bgColourDefault">
        @yield ('content')
    </div>
	<footer class="flex items-center justify-between flex-wrap bg-blue-900 z-10 top-0 sticky lg:px-40 sm:px-0">
		<span class="block text-sm text-white sm:text-center dark:text-gray-400">© 2023 Institut Teknologi Sepuluh Nopember
		</span>
		<img class="h-20 w-30" src="/pictures/ITS_Footer.png" alt="">
	</footer>
<script>
    //Javascript to toggle the menu
    document.getElementById('nav-toggle').onclick = function(){
        document.getElementById("nav-content").classList.toggle("hidden");
    }
</script>

</body>
</html>