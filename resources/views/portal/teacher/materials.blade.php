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

@section('title', 'Materials Upload')
@section('page-title', 'Materials Library')
@section('page-subtitle', 'Upload dan kelola materi pembelajaran musik untuk siswa.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">
    {{-- Upload Form Card --}}
    <section class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-fit">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Upload Baru</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Tambahkan file materi baru.</p>
            </div>
            <i data-lucide="upload-cloud" class="w-4 h-4 text-slate-300"></i>
        </div>

        <form class="p-6 flex flex-col gap-4" method="POST" enctype="multipart/form-data" action="{{ route('teacher.materials.store') }}">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Class</label>
                <select name="class_id" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
                    <option value="">General (All Classes)</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Title</label>
                <input type="text" name="title" required placeholder="Judul Materi..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat..." class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 focus:border-blue-500 focus:ring-0"></textarea>
            </div>

            <div class="p-4 rounded-2xl border-2 border-dashed border-slate-100 bg-slate-50 flex flex-col items-center justify-center text-center group hover:border-blue-200 hover:bg-blue-50/30 transition-all cursor-pointer relative">
                <input type="file" name="file" required class="absolute inset-0 opacity-0 cursor-pointer">
                <div class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-slate-300 group-hover:text-blue-500 shadow-sm transition-all mb-2">
                    <i data-lucide="file-plus" class="w-5 h-5"></i>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pilih File Materi</p>
                <p class="text-[9px] text-slate-300 mt-1">PDF, MP3, MP4, or Images</p>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-2xl bg-blue-600 text-white text-xs font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95 flex items-center justify-center gap-2 mt-2">
                <i data-lucide="upload" class="w-4 h-4"></i> PUBLISH MATERIAL
            </button>
        </form>
    </section>

    {{-- Materials List Card --}}
    <section class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div>
                <h3 class="text-base font-bold text-slate-900 leading-none">Materi Saya</h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Daftar file yang telah Anda unggah.</p>
            </div>
            <i data-lucide="folder" class="w-4 h-4 text-slate-300"></i>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Material Info</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target Class</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Download</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($materials as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100">
                                        @php
                                            $ext = pathinfo($row->file_path, PATHINFO_EXTENSION);
                                            $icon = 'file-text';
                                            if(in_array($ext, ['mp3', 'wav'])) $icon = 'music';
                                            if(in_array($ext, ['mp4', 'mov'])) $icon = 'video';
                                            if(in_array($ext, ['jpg', 'png', 'jpeg'])) $icon = 'image';
                                        @endphp
                                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700 leading-none">{{ $row->title }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">{{ Str::limit($row->description, 40) ?: 'No description' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                @if($row->class)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-bold border border-blue-100">
                                        {{ $row->class->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-slate-50 text-slate-400 text-[10px] font-bold border border-slate-100 uppercase tracking-wider">
                                        General
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                @if($row->file_path)
                                    <a href="{{ asset('storage/'.$row->file_path) }}" target="_blank" class="h-9 w-9 inline-flex items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-100 hover:bg-blue-700 transition-all active:scale-95">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                                        <i data-lucide="files" class="w-8 h-8"></i>
                                    </div>
                                    <h4 class="text-slate-900 font-bold text-sm">Belum ada materi</h4>
                                    <p class="text-slate-400 text-xs">Anda belum mengunggah materi pembelajaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection