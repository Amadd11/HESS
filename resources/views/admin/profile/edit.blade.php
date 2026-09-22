@extends('layouts.admin', ['title' => 'Pengaturan Akun — HESS Admin'])

@section('header-title', 'Pengaturan Akun Administrator')

@section('content')
<div class="space-y-6 max-w-4xl">

    <!-- Top Action Bar Header -->
    <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center shrink-0 border border-primary-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Kelola Akun & Keamanan Akses</h2>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui nama administrator, alamat email login, serta kata sandi panel survei HESS.</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <x-button variant="secondary" size="md" :href="route('admin.dashboard')">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Dashboard</span>
            </x-button>
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Form 1: Update Profil Identitas -->
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Informasi Profil Admin</h3>
                        <p class="text-[11px] text-gray-500">Nama lengkap dan email aktif untuk autentikasi sistem.</p>
                    </div>
                </div>

                <form id="profile-form" action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input
                            label="Nama Lengkap"
                            name="name"
                            type="text"
                            :value="$user->name"
                            required
                            placeholder="Contoh: Administrator HESS"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Alamat Email"
                            name="email"
                            type="email"
                            :value="$user->email"
                            required
                            placeholder="nama@email.com"
                        />
                        <p class="text-[10px] text-gray-400 mt-1">Digunakan untuk proses masuk ke dashboard admin HESS.</p>
                    </div>
                </form>
            </div>

            <div class="pt-5 mt-6 border-t border-gray-100 flex items-center justify-end">
                <x-button type="submit" form="profile-form" variant="primary" size="md">
                    <span>Simpan Perubahan Profil</span>
                </x-button>
            </div>
        </div>

        <!-- Form 2: Ganti Kata Sandi -->
        <div class="bg-white p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-5">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Perbarui Kata Sandi</h3>
                        <p class="text-[11px] text-gray-500">Gunakan kata sandi kombinasi kuat minimal 8 karakter.</p>
                    </div>
                </div>

                <form id="password-form" action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input
                            label="Kata Sandi Saat Ini"
                            name="current_password"
                            type="password"
                            required
                            placeholder="Masukkan password saat ini"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Kata Sandi Baru"
                            name="password"
                            type="password"
                            required
                            placeholder="Minimal 8 karakter"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Ulangi Kata Sandi Baru"
                            name="password_confirmation"
                            type="password"
                            required
                            placeholder="Ketik ulang password baru"
                        />
                    </div>
                </form>
            </div>

            <div class="pt-5 mt-6 border-t border-gray-100 flex items-center justify-end">
                <x-button type="submit" form="password-form" variant="primary" size="md">
                    <span>Perbarui Kata Sandi</span>
                </x-button>
            </div>
        </div>

    </div>

</div>
@endsection
