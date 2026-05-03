@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','My Class')
@section('page-title','My Class Information')
@section('content')
<section class="card">
    <div class="ui-card-header">
        <h3 class="ui-card-title">Enrolled Class Details</h3>
        <p class="ui-card-subtitle">Informasi detail mengenai kelas yang sedang Anda ikuti.</p>
    </div>
    
    @if($assignedClass)
        <div class="info-grid">
            <div class="info-item">
                <label>Class Name</label>
                <div class="info-value">{{ $assignedClass->name }}</div>
            </div>
            <div class="info-item">
                <label>Teacher</label>
                <div class="info-value">{{ $assignedClass->teacher?->name ?? 'TBA' }}</div>
            </div>
            @if($assignedClass->price)
            <div class="info-item">
                <label>Course Fee</label>
                <div class="info-value">Rp {{ number_format($assignedClass->price, 0, ',', '.') }} / Month</div>
            </div>
            @endif
            <div class="info-item">
                <label>Status</label>
                <div class="info-value"><span class="badge badge-success">ACTIVE</span></div>
            </div>
        </div>
    @else
        <x-ui.empty-state title="No class assigned" description="Belum ada data kelas yang terdaftar untuk akun Anda." icon="music-2" />
    @endif
</section>

<style>
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}
.info-item label {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
    margin-bottom: 0.25rem;
}
.info-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #0f172a;
}
.badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 700;
}
.badge-success { background: #dcfce7; color: #166534; }
</style>
@endsection