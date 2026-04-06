@extends('admin.layout')

@section('title', 'Settings | ROFC Admin')
@section('page-title', 'Settings')

@section('content')
<section class="admin-card" style="max-width: 860px;">
    <h2>Pengaturan Website & Sekolah</h2>
    <form class="form-stack">
        <label>Nama Sekolah <input type="text" value="{{ $settings['school_name'] }}"></label>
        <label>Logo <input type="file"></label>
        <label>Alamat <textarea rows="3">{{ $settings['address'] }}</textarea></label>
        <label>Email <input type="email" value="{{ $settings['email'] }}"></label>
        <label>WhatsApp <input type="text" value="{{ $settings['whatsapp'] }}"></label>
        <label>Instagram <input type="text" placeholder="https://instagram.com/rofcschoolmusic"></label>
        <label>YouTube <input type="text" placeholder="https://youtube.com/@rofcschoolmusic"></label>
        <label>Footer Text <input type="text" value="{{ $settings['footer_text'] }}"></label>
        <button class="btn-admin" type="button">Simpan Pengaturan</button>
    </form>
</section>
@endsection
