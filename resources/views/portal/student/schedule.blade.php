@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Class Schedule')
@section('page-title','My Learning Schedule')
@section('content')
<section class="card">
    <div class="ui-card-header">
        <h3 class="ui-card-title">Booked Time Slots</h3>
        <p class="ui-card-subtitle">Jadwal latihan rutin Anda berdasarkan slot yang telah dibooking.</p>
    </div>

    @if($schedules->isNotEmpty())
        <div class="table-wrap">
            <table class="att-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th>Status</th>
                        <th>Attendance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        @php
                            $pendingRequest = \App\Models\RescheduleRequest::where('student_id', $student->id)
                                ->where('old_session_id', $sched->id)
                                ->where('status', 'pending')
                                ->first();
                        @endphp
                        <tr>
                            <td><strong>{{ $sched->session_date->translatedFormat('l, d M Y') }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($sched->time)->format('H:i') }}</td>
                            <td>{{ $sched->musicClass->name ?? '-' }}</td>
                            <td>{{ $sched->teacher->name ?? '-' }}</td>
                            <td>
                                @if($sched->status === 'completed')
                                    <span class="att-badge att-badge-present">Selesai</span>
                                @elseif($sched->status === 'rescheduled')
                                    <span class="att-badge att-badge-reschedule">Rescheduled</span>
                                @else
                                    <span class="att-badge" style="background-color: #f3f4f6; color: #4b5563;">Booked</span>
                                @endif
                            </td>
                            <td>
                                @if($sched->attendance)
                                    @php $status = strtolower($sched->attendance->status); @endphp
                                    <span class="att-badge att-badge-{{ $status }}">
                                        @if($status === 'present') ✔
                                        @elseif($status === 'absent') ✖
                                        @elseif($status === 'reschedule') ↻
                                        @endif
                                        {{ $status }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($pendingRequest)
                                    <span class="att-badge att-badge-reschedule">Pending Request</span>
                                @elseif(!$sched->attendance && $sched->status === 'booked')
                                    <button 
                                        type="button" 
                                        class="btn-reschedule"
                                        data-old-id="{{ $sched->id }}"
                                        data-old-label="{{ $sched->session_date->translatedFormat('l, d M Y') }} - {{ \Carbon\Carbon::parse($sched->time)->format('H:i') }}"
                                        data-teacher-id="{{ $sched->teacher_id }}"
                                        data-class-id="{{ $sched->class_id }}"
                                        onclick="openRescheduleModal(this)"
                                    >
                                        <i data-lucide="refresh-cw"></i> Reschedule
                                    </button>
                                @else
                                    <span class="text-muted" title="Cannot reschedule if attendance recorded or not booked">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-ui.empty-state title="No schedule available" description="Belum ada slot waktu yang dibooking untuk Anda saat ini." icon="calendar-days" />
    @endif
</section>

@push('modals')
<!-- Reschedule Modal -->
<div id="rescheduleModal" class="fixed inset-0 bg-black/10 flex items-center justify-center z-[9999] px-4" style="display:none;">
    <div class="absolute inset-0" onclick="closeRescheduleModal()"></div>
    <div class="relative bg-white rounded-xl shadow-md border border-gray-200 p-6 w-full max-w-lg overflow-hidden transition-all">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Request Reschedule</h3>
                <p class="text-sm text-gray-500 mt-1">Pindahkan jadwal latihan Anda ke slot lain.</p>
            </div>
            <button type="button" onclick="closeRescheduleModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-full">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('student.schedule.reschedule.request') }}" method="POST" id="rescheduleForm" class="space-y-5">
            @csrf
            <input type="hidden" name="old_session_id" id="old_session_id">
            
            <!-- Current Schedule Box -->
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 transition-all">
                <label class="text-xs text-gray-500 block mb-1">Current Schedule</label>
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                    <p id="old_schedule_label_text" class="text-sm font-semibold text-gray-800">Loading...</p>
                </div>
                <input type="hidden" id="old_schedule_label">
            </div>

            <!-- New Schedule Select Box -->
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 focus-within:border-blue-300 transition-all">
                <label for="new_schedule_id" class="text-xs text-gray-500 block mb-1 cursor-pointer">New Schedule</label>
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                    <select name="new_schedule_id" id="new_schedule_id" required 
                        class="w-full bg-transparent text-gray-800 text-sm font-semibold outline-none cursor-pointer appearance-none">
                        <option value="">-- Select New Slot --</option>
                    </select>
                </div>
                <p class="text-[11px] text-gray-400 mt-2 flex items-center gap-1">
                    <i data-lucide="info" class="w-3 h-3"></i> Only available slots are shown
                </p>
            </div>

            <!-- Reason Box -->
            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 focus-within:border-blue-300 transition-all">
                <label for="reason" class="text-xs text-gray-500 block mb-1 cursor-pointer">Reason (Optional)</label>
                <textarea name="reason" id="reason" rows="3" 
                    class="w-full bg-transparent text-gray-800 text-sm font-medium outline-none resize-none placeholder:text-gray-400"
                    placeholder="Why do you need to reschedule?"></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeRescheduleModal()" 
                    class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-sm font-semibold transition-all">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-6 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm font-bold transition-all transform active:scale-95">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

<style>
.att-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
.att-table th { text-align: left; padding: 0.75rem; background: rgba(148, 163, 184, 0.05); border-bottom: 2px solid rgba(148, 163, 184, 0.1); color: var(--muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; }
.att-table td { padding: 1rem 0.75rem; border-bottom: 1px solid rgba(148, 163, 184, 0.1); color: var(--text); font-size: 0.9rem; }

.att-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; }
.att-badge-present { background: rgba(34, 197, 94, 0.15); color: #86efac; }
.att-badge-absent  { background: rgba(239, 68, 68, 0.15); color: #fca5a5; }
.att-badge-reschedule { background: rgba(245, 158, 11, 0.15); color: #fde68a; }

.btn-reschedule { display: inline-flex; align-items: center; gap: 0.5rem; background: var(--primary); color: #fff; border: 0; padding: 0.4rem 0.75rem; border-radius: 0.5rem; cursor: pointer; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; }
.btn-reschedule:hover { transform: translateY(-1px); filter: brightness(1.1); box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3); }
.btn-reschedule i { width: 0.85rem; height: 0.85rem; }

.text-muted { color: var(--muted); font-style: italic; }
</style>

<script>
function openRescheduleModal(btn) {
    const oldId = btn.getAttribute('data-old-id');
    const oldLabel = btn.getAttribute('data-old-label');
    const teacherId = btn.getAttribute('data-teacher-id');
    const classId = btn.getAttribute('data-class-id');

    document.getElementById('old_session_id').value = oldId;
    document.getElementById('old_schedule_label').value = oldLabel;
    document.getElementById('old_schedule_label_text').textContent = oldLabel;
    document.getElementById('rescheduleModal').style.display = 'flex';

    const select = document.getElementById('new_schedule_id');
    select.innerHTML = '<option value="">Loading slots...</option>';

    fetch(`/student/schedule/available-slots?teacher_id=${teacherId}&class_id=${classId}`)
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '<option value="">-- Select New Slot --</option>';
            if (data.grouped) {
                Object.keys(data.grouped).forEach(day => {
                    const group = document.createElement('optgroup');
                    group.label = day;
                    data.grouped[day].forEach(slot => {
                        const opt = document.createElement('option');
                        opt.value = slot.id;
                        opt.textContent = `${slot.day} - ${slot.time}`;
                        group.appendChild(opt);
                    });
                    select.appendChild(group);
                });
            }
        });
}

function closeRescheduleModal() {
    document.getElementById('rescheduleModal').style.display = 'none';
}
</script>
@endsection