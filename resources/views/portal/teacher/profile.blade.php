@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard')],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index')],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index')],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index')],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index')],
        ['label' => 'Materials', 'url' => route('teacher.materials.index')],
        ['label' => 'Profile', 'url' => route('teacher.profile.index')],
    ];
    $panelTitle = 'Teacher Portal';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'Profil Saya')
{{-- Deployment trigger: update secrets --}}
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi akun dan pengaturan keamanan Anda')

@section('content')
<div class="max-w-4xl mx-auto mt-4">
    <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Avatar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center">
                    <div class="relative group">
                        <div class="h-32 w-32 rounded-full border-4 border-slate-50 overflow-hidden shadow-inner bg-slate-50 flex items-center justify-center">
                            @if($teacher->photo_path)
                                <img id="avatar-preview" src="{{ asset('storage/' . $teacher->photo_path) }}" alt="Profile" class="h-full w-full object-cover">
                            @else
                                <div id="avatar-placeholder" class="h-full w-full flex items-center justify-center bg-blue-50 text-blue-600 text-3xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <img id="avatar-preview" src="#" alt="Preview" class="hidden h-full w-full object-cover">
                            @endif
                        </div>
                        <label for="photo-upload" class="absolute bottom-0 right-0 h-10 w-10 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:bg-blue-700 transition-all border-4 border-white">
                            <i data-lucide="camera" class="w-4 h-4"></i>
                            <input type="file" name="photo" id="photo-upload" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </label>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $user->name }}</h3>
                    <p class="text-sm text-slate-500">Teacher</p>
                    
                    <div class="mt-6 w-full space-y-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-left px-2">Status Akun</p>
                        <div class="flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2 rounded-2xl border border-green-100">
                            <div class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-xs font-bold uppercase tracking-wider">Akun Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Forms --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Profile Info Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                        <div class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-900">Informasi Pribadi</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Nama Lengkap</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-slate-400 pointer-events-none">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-50 transition-all text-sm font-medium text-slate-700" 
                                    placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Alamat Email</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-slate-300 pointer-events-none">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </span>
                                <input type="email" value="{{ $user->email }}" readonly
                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-100 bg-slate-50/30 text-slate-400 cursor-not-allowed text-sm font-medium" 
                                    placeholder="your@email.com">
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1.5 px-1 italic">Email tidak dapat diubah</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Nomor HP</label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-slate-400 pointer-events-none">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </span>
                                <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" 
                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-50 transition-all text-sm font-medium text-slate-700" 
                                    placeholder="0812xxxxxx">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security Card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center gap-3">
                        <div class="h-8 w-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <h3 class="font-bold text-slate-900">Keamanan Akun</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="bg-amber-50 rounded-2xl p-4 border border-amber-100 flex items-center gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-amber-600 shrink-0"></i>
                            <p class="text-[11px] text-amber-700 leading-relaxed">Kosongkan jika tidak ingin mengubah password. Password baru minimal 8 karakter.</p>
                        </div>
                        
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Password Lama</label>
                            <div class="relative flex items-center group">
                                <span class="absolute left-4 text-slate-400 pointer-events-none">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </span>
                                <input type="password" name="current_password" id="current_password"
                                    class="w-full pl-11 pr-12 py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-50 transition-all text-sm font-medium text-slate-700" 
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePassword('current_password')" class="absolute right-2 h-9 w-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-0 border-none bg-transparent transition-all duration-200" style="border:none; outline:none; background:transparent;">
                                    <i data-lucide="eye" class="w-4 h-4" id="eye-current_password"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Password Baru</label>
                                <div class="relative flex items-center group">
                                    <span class="absolute left-4 text-slate-400 pointer-events-none">
                                        <i data-lucide="key-round" class="w-4 h-4"></i>
                                    </span>
                                    <input type="password" name="new_password" id="new_password"
                                        class="w-full pl-11 pr-12 py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-50 transition-all text-sm font-medium text-slate-700" 
                                        placeholder="Min. 8 karakter">
                                    <button type="button" onclick="togglePassword('new_password')" class="absolute right-2 h-9 w-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-0 border-none bg-transparent transition-all duration-200" style="border:none; outline:none; background:transparent;">
                                        <i data-lucide="eye" class="w-4 h-4" id="eye-new_password"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Konfirmasi Password Baru</label>
                                <div class="relative flex items-center group">
                                    <span class="absolute left-4 text-slate-400 pointer-events-none">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                    </span>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                        class="w-full pl-11 pr-12 py-3 rounded-2xl border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-blue-50 transition-all text-sm font-medium text-slate-700" 
                                        placeholder="Ulangi password baru">
                                    <button type="button" onclick="togglePassword('new_password_confirmation')" class="absolute right-2 h-9 w-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-0 border-none bg-transparent transition-all duration-200" style="border:none; outline:none; background:transparent;">
                                        <i data-lucide="eye" class="w-4 h-4" id="eye-new_password_confirmation"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('teacher.dashboard') }}" class="px-6 py-3 rounded-2xl text-sm font-bold text-slate-500 hover:bg-slate-100 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 rounded-2xl bg-blue-600 text-white text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        const eyeIcon = document.getElementById('eye-' + fieldId);
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            eyeIcon.setAttribute('data-lucide', 'eye');
        }
        
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
</script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            if (window.showToast) window.showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            if (window.showToast) window.showToast("{{ session('error') }}", 'error');
        @endif
        @if($errors->any())
            if (window.showToast) window.showToast("Ada kesalahan pada input Anda. Silakan periksa kembali.", 'error');
        @endif
    });
</script>
@endpush

<style>
    /* Utility Overrides */
    .mx-auto { margin-left: auto; margin-right: auto; }
    .max-w-4xl { max-width: 56rem; }
    .mt-4 { margin-top: 1rem; }
    .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: 1.5rem; }
    .grid { display: grid; }
    .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
    .gap-8 { gap: 2rem; }
    .rounded-3xl { border-radius: 1.5rem; }
    .shadow-sm { box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); }
    .border { border-width: 1px; }
    .border-slate-100 { border-color: rgb(241 245 249); }
    .p-8 { padding: 2rem; }
    .flex { display: flex; }
    .items-center { align-items: center; }
    .text-center { text-align: center; }
    .h-32 { height: 8rem; }
    .w-32 { width: 8rem; }
    .rounded-full { border-radius: 9999px; }
    .h-full { height: 100%; }
    .w-full { width: 100%; }
    .relative { position: relative; }
    .absolute { position: absolute; }
    .bottom-0 { bottom: 0px; }
    .right-0 { right: 0px; }
    .h-10 { height: 2.5rem; }
    .w-10 { width: 2.5rem; }
    .bg-blue-600 { background-color: rgb(37 99 235); }
    .text-white { color: rgb(255 255 255); }
    .cursor-pointer { cursor: pointer; }
    .shadow-lg { box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1); }
    .border-4 { border-width: 4px; }
    .border-white { border-color: rgb(255 255 255); }
    .w-4 { width: 1rem; }
    .h-4 { height: 1rem; }
    .hidden { display: none; }
    .text-lg { font-size: 1.125rem; }
    .font-bold { font-weight: 700; }
    .text-slate-900 { color: rgb(15 23 42); }
    .text-sm { font-size: 0.875rem; }
    .text-slate-500 { color: rgb(100 116 139); }
    .text-slate-400 { color: rgb(148 163 184); }
    .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
    .gap-2 { gap: 0.5rem; }
    .bg-green-50 { background-color: rgb(240 253 244); }
    .text-green-700 { color: rgb(21 128 61); }
    .px-4 { padding-left: 1rem; padding-right: 1rem; }
    .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .rounded-2xl { border-radius: 1rem; }
    .h-1\.5 { height: 0.375rem; }
    .w-1\.5 { width: 0.375rem; }
    .bg-green-500 { background-color: rgb(34 197 94); }
    .lg\:col-span-1 { grid-column: span 1 / span 1; }
    .lg\:col-span-2 { grid-column: span 2 / span 2; }
    .gap-6 { gap: 1.5rem; }
    .px-8 { padding-left: 2rem; padding-right: 2rem; }
    .py-5 { padding-top: 1.25rem; padding-bottom: 1.25rem; }
    .bg-slate-50\/30 { background-color: rgba(248, 250, 252, 0.3); }
    .h-8 { height: 2rem; }
    .w-8 { width: 2rem; }
    .bg-blue-50 { background-color: rgb(239 246 255); }
    .text-blue-600 { color: rgb(37 99 235); }
    .md\:col-span-2 { grid-column: span 2 / span 2; }
    .text-xs { font-size: 0.75rem; }
    .pl-11 { padding-left: 2.75rem; }
    .pr-4 { padding-right: 1rem; }
    .pr-12 { padding-right: 3rem; }
    .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .bg-slate-50\/50 { background-color: rgba(248, 250, 252, 0.5); }
    .font-medium { font-weight: 500; }
    .left-4 { left: 1rem; }
    .bg-amber-50 { background-color: rgb(255 251 235); }
    .text-amber-600 { color: rgb(217 119 6); }
    .text-amber-700 { color: rgb(180 83 9); }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

    /* Eye icon button cleanup */
    button[onclick^="togglePassword"] {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background-color: transparent !important;
        -webkit-tap-highlight-color: transparent;
    }
    
    button[onclick^="togglePassword"]:focus,
    button[onclick^="togglePassword"]:active,
    button[onclick^="togglePassword"]:focus-visible {
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
        background-color: rgba(59, 130, 246, 0.05) !important;
    }
</style>
@endsection
