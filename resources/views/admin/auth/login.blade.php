<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f7f4f8]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — HESS</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 selection:bg-primary-500 selection:text-white font-sans antialiased">
    <div class="max-w-md w-full">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-gradient-to-tr from-primary-700 to-primary-500 rounded-2xl flex items-center justify-center text-white text-2xl font-black mx-auto mb-3 shadow-lg shadow-primary-600/30">
                H
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">HESS Admin Portal</h1>
            <p class="text-xs text-gray-500 mt-1">Hospital Employee Satisfaction Survey Dashboard</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-200/80">
            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">
                        Email Administrator
                    </label>
                    <input type="email" name="email" value="{{ old('email', 'admin@hess.id') }}" required autofocus
                           placeholder="nama@rumah-sakit.com"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                    </div>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full h-12 px-4 rounded-xl border border-gray-300 text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-gray-300">
                        <span class="text-xs text-gray-600 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Tombol Masuk -->
                <div class="pt-3">
                    <x-button type="submit" variant="primary" size="lg" class="w-full">
                        <span>Masuk ke Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </x-button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <a href="{{ route('survey.index') }}" class="text-xs text-primary-600 hover:text-primary-800 font-bold transition">
                    ← Kembali ke Kuesioner Publik
                </a>
            </div>
        </div>
    </div>
</body>
</html>
