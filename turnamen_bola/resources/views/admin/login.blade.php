<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panitia Pusat Super Admin - Piala Disdikpora Kebumen</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full m-0 p-0 overflow-x-hidden bg-slate-900 text-white">

    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 bg-blue-600/20 text-blue-400 rounded-xl mb-3">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">PANITIA PUSAT</h1>
                <p class="text-xs text-slate-400 mt-1">Portal Super Admin Disdikpora Regional Kebumen</p>
            </div>

            @if(session('error'))
                <div class="mb-4 text-sm text-red-400 bg-red-950/50 border border-red-800/50 p-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 text-sm text-red-400 bg-red-950/50 border border-red-800/50 p-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Administrator</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm text-white transition duration-150" placeholder="admin@disdikpora.id" required autofocus>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm text-white transition duration-150" placeholder="••••••••" required>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 active:scale-[0.99] text-white font-semibold py-3 px-4 rounded-lg shadow-lg transition duration-200 text-sm uppercase tracking-wider">
                        Masuk ke Dashboard
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('operator.login') }}" class="text-xs text-slate-400 hover:text-blue-400 transition">
                    &larr; Halaman Login Operator SSB
                </a>
            </div>
        </div>
    </div>

</body>
</html>
