<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Operator SSB - Piala Disdikpora Grassroot Regional Kebumen</title>
    
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Memanggil Tailwind CSS via Vite Laravel 12 -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="h-full m-0 p-0 overflow-x-hidden bg-white">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 w-full" role="main">
        
        <!-- BAGIAN KIRI: FORM LOGIN (Lebar 5 Kolom) -->
        <div class="lg:col-span-5 flex flex-col justify-between p-8 sm:p-12 lg:p-16 bg-white z-10 shadow-2xl lg:shadow-none">
            
            <!-- Logo / Brand Header -->
            <div>
                <div class="flex items-center space-x-2 text-blue-700 font-bold text-xl tracking-tight">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <span>Disdikpora Kebumen</span>
                </div>
            </div>

            <!-- Form Utama: area utama untuk login operator -->
            <div class="my-auto py-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Login Operator</h1>
                    <p class="text-sm text-slate-500 mt-1">Selamat Datang Operator SSB</p>
                </div>
                {{-- Menampilkan pesan flash / error umum --}}
                @if(session('error'))
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-100 p-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-100 p-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/operator/login') }}" method="POST" class="space-y-5" novalidate>
                    @csrf

                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nama Pengguna*</label>
                        <input id="username" type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm text-slate-800 transition duration-150" placeholder="Nama Pengguna" required autofocus aria-label="Nama Pengguna" autocomplete="username">
                        @error('username')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi*</label>
                        <input id="password" type="password" name="password" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm text-slate-800 transition duration-150" placeholder="Kata Sandi" required aria-label="Kata Sandi" autocomplete="current-password">
                        @error('password')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-200 text-sm uppercase tracking-wider cursor-pointer">
                            Masuk ke Panel Operator
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer / Kembali ke Halaman Depan -->
            <div>
                <a href="#" class="text-xs text-slate-500 hover:text-blue-600 flex items-center transition duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke halaman depan
                </a>
            </div>

        </div>

        <!-- BAGIAN KANAN: DEKORASI / ILUSTRASI (Lebar 7 Kolom) -->
        <div class="hidden lg:col-span-7 lg:flex flex-col items-center justify-center bg-gradient-to-br from-blue-700 via-blue-800 to-slate-900 p-12 relative overflow-hidden">
            
            <!-- Efek Pola Latar Belakang Abstrak -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>

            <!-- Ilustrasi / Grafik Modern -->
            <div class="relative z-10 max-w-lg text-center text-white">
                <div class="bg-white/10 backdrop-blur-md p-8 rounded-3xl border border-white/20 shadow-2xl mb-6">
                    <svg class="w-24 h-24 mx-auto text-blue-200 mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h2 class="text-xl font-bold mb-2">Piala Disdikpora Grassroot Regional Kebumen</h2>
                    <p class="text-xs text-blue-100 leading-relaxed">Sistem Pendataan Buku Data Pemain & Kartu Pemain Berbasis Digital Terpadu.</p>
                </div>
            </div>

        </div>

    </div>

</body>
</html>