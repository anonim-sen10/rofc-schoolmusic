@php
    $menuItems = [
        ['label' => 'Dashboard', 'url' => route('teacher.dashboard'), 'key' => 'dashboard'],
        ['label' => 'My Classes', 'url' => route('teacher.my-classes.index'), 'key' => 'my_classes'],
        ['label' => 'My Schedule', 'url' => route('teacher.schedule.index'), 'key' => 'my_schedule'],
        ['label' => 'Student Progress', 'url' => route('teacher.student-progress.index'), 'key' => 'student_progress'],
        ['label' => 'My Students', 'url' => route('teacher.my-students.index'), 'key' => 'my_students'],
        ['label' => 'Materials', 'url' => route('teacher.materials.index'), 'key' => 'materials'],
    ];
    $panelTitle = 'ROFC - Rock For Change';
    $homeRoute = route('teacher.dashboard');
@endphp

@extends('portal.layouts.app')

@section('title', 'My Classes | ROFC Teacher Portal')

@section('content')
<div class="premium-wrapper">
    <!-- Header Section -->
    <header class="dashboard-header">
        <div class="header-left">
            <h1>Active Programs</h1>
            <p>View and manage music classes assigned to your schedule.</p>
        </div>
        <div class="header-right">
            <button class="action-icon-btn"><i data-lucide="search"></i></button>
            <button class="action-icon-btn"><i data-lucide="sliders-horizontal"></i></button>
        </div>
    </header>

    <!-- Premium Table Card -->
    <div class="table-card-premium">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Description</th>
                        <th>Students</th>
                        <th>Schedule</th>
                        <th class="text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr class="premium-row">
                            <td>
                                <div class="class-cell">
                                    <div class="icon-box">
                                        <i data-lucide="music-2"></i>
                                    </div>
                                    <span class="class-name">{{ $class->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="text-description">{{ $class->description ?: 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="student-trigger-pill" onclick="showStudentModal('{{ $class->id }}', '{{ $class->name }}')">
                                    <i data-lucide="users-2"></i>
                                    <span>{{ $class->students_count ?? 0 }} Student</span>
                                </div>
                            </td>
                            <td>
                                <div class="schedule-info">
                                    <i data-lucide="calendar-days"></i>
                                    <span>{{ $class->schedules_count ?? 0 }} {{ Str::plural('Schedule', $class->schedules_count ?? 0) }}</span>
                                </div>
                            </td>
                            <td class="text-right">
                                <span class="status-pill {{ $class->status === 'active' ? 'active' : '' }}">
                                    <span class="dot"></span>
                                    {{ ucfirst($class->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-table-cell">
                                <div class="empty-state">
                                    <i data-lucide="folder-open"></i>
                                    <p>No active classes at the moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Global Fixed Modal Overlay -->
<div id="premiumStudentModal" class="fixed-modal-overlay">
    <div class="modal-container-centered">
        <div class="modal-premium-card">
            <header class="modal-premium-header">
                <div class="header-content">
                    <div class="header-icon-box">
                        <i data-lucide="users"></i>
                    </div>
                    <div>
                        <h3 id="modalClassName">Student Enrollment</h3>
                        <p>Class participants and schedule overview</p>
                    </div>
                </div>
                <button class="modal-close-icon" onclick="hideStudentModal()">
                    <i data-lucide="x"></i>
                </button>
            </header>
            
            <div class="modal-premium-body">
                <div id="modalStudentList" class="premium-student-grid">
                    <!-- Students will be injected here by JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .premium-wrapper {
        font-family: 'Inter', sans-serif;
        color: #0f172a;
        padding-bottom: 3rem;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .dashboard-header h1 { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.025em; margin: 0; }
    .dashboard-header p { color: #64748b; font-size: 0.95rem; margin-top: 0.25rem; }

    .action-icon-btn {
        width: 2.75rem; height: 2.75rem;
        background: white; border: 1px solid #e2e8f0; border-radius: 0.85rem;
        color: #64748b; cursor: pointer; transition: all 0.2s;
        display: inline-flex; align-items: center; justify-content: center; margin-left: 0.5rem;
    }
    .action-icon-btn:hover { border-color: #6366f1; color: #6366f1; transform: translateY(-1px); }

    .table-card-premium {
        background: white;
        border-radius: 2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        padding: 1.25rem;
    }

    .table-responsive { overflow-x: auto; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    th {
        padding: 1rem 1.25rem; text-align: left;
        font-size: 0.75rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 1px solid #f1f5f9;
    }

    td { padding: 1.25rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; font-size: 0.9rem; }
    .premium-row { transition: background 0.2s; }
    .premium-row:hover { background: #fbfcfe; }

    .class-cell { display: flex; align-items: center; gap: 1rem; }
    .icon-box {
        width: 2.5rem; height: 2.5rem; background: #f1f5f9;
        color: #6366f1; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
    }
    .class-name { font-weight: 700; color: #0f172a; }
    .text-description { color: #64748b; font-size: 0.85rem; max-width: 200px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.35rem 0.75rem; border-radius: 2rem;
        font-size: 0.75rem; font-weight: 700; background: #f1f5f9; color: #64748b;
    }
    .status-pill.active { background: #ecfdf5; color: #059669; }
    .status-pill .dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    .schedule-info { display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.85rem; }
    .schedule-info i { width: 1rem; height: 1rem; color: #94a3b8; }

    .text-right { text-align: right; }

    /* Trigger Pill Styling */
    .student-trigger-pill {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 0.85rem; background: #f1f5f9; border-radius: 0.85rem;
        font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s;
        border: 1px solid transparent;
    }
    .student-trigger-pill:hover { background: #e2e8f0; color: #6366f1; border-color: #6366f1; transform: translateY(-1px); }

    /* GLOBAL FIXED MODAL STYLING */
    .fixed-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
        opacity: 0; visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .fixed-modal-overlay.active { opacity: 1; visibility: visible; }

    .modal-container-centered {
        width: min(520px, 95vw);
        transform: scale(0.9) translateY(20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .fixed-modal-overlay.active .modal-container-centered { transform: scale(1) translateY(0); }

    .modal-premium-card {
        background: white; border-radius: 2.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .modal-premium-header {
        padding: 2rem; border-bottom: 1px solid #f1f5f9;
        display: flex; justify-content: space-between; align-items: flex-start;
        background: linear-gradient(to bottom right, #ffffff, #fbfcfe);
    }

    .header-content { display: flex; gap: 1rem; align-items: center; }
    .header-icon-box {
        width: 3.25rem; height: 3.25rem; background: rgba(99, 102, 241, 0.1);
        color: #6366f1; border-radius: 1.1rem;
        display: flex; align-items: center; justify-content: center;
    }
    .header-icon-box i { width: 1.5rem; height: 1.5rem; }
    
    .modal-premium-header h3 { font-size: 1.25rem; font-weight: 800; margin: 0; color: #0f172a; }
    .modal-premium-header p { font-size: 0.85rem; color: #64748b; margin: 0.25rem 0 0; }

    .modal-close-icon {
        width: 2.25rem; height: 2.25rem; background: #f1f5f9; border: none;
        color: #64748b; border-radius: 0.75rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center; transition: all 0.2s;
    }
    .modal-close-icon:hover { background: #e2e8f0; color: #0f172a; transform: rotate(90deg); }

    .modal-premium-body { padding: 1.5rem 2rem 2.5rem; max-height: 70vh; overflow-y: auto; }
    .premium-student-grid { display: flex; flex-direction: column; gap: 1rem; }

    .student-premium-item {
        padding: 1.25rem; background: #f8fafc; border-radius: 1.5rem;
        border: 1px solid #f1f5f9; transition: all 0.2s;
        display: flex; flex-direction: column; gap: 1rem;
    }
    .student-premium-item:hover { border-color: #6366f1; background: #ffffff; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }

    .student-row-top { display: flex; justify-content: space-between; align-items: center; }
    .student-avatar-box { display: flex; align-items: center; gap: 1rem; }
    .avatar-circle {
        width: 3rem; height: 3rem; background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white; border-radius: 1rem; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.25rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    .student-name-text { font-weight: 800; color: #0f172a; font-size: 1.05rem; }
    .student-status-badge { font-size: 0.7rem; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 2rem; display: inline-block; margin-top: 0.125rem; }
    .student-status-badge.active { background: #ecfdf5; color: #059669; }

    .btn-progress-premium {
        padding: 0.5rem 1rem; background: white; border: 1.5px solid #6366f1;
        color: #6366f1; border-radius: 0.75rem; font-size: 0.8rem; font-weight: 700;
        text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
        transition: all 0.2s;
    }
    .btn-progress-premium:hover { background: #6366f1; color: white; }

    .student-row-meta { display: flex; gap: 1.5rem; padding-top: 0.75rem; border-top: 1px solid #f1f5f9; }
    .meta-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #64748b; font-weight: 500; }
    .meta-item i { width: 0.9rem; height: 0.9rem; color: #6366f1; }

    .empty-state-text { text-align: center; color: #94a3b8; padding: 2rem; font-size: 0.9rem; }
</style>

@push('scripts')
<script>
    const classRecords = @json($classes);

    function showStudentModal(classId, className) {
        const modal = document.getElementById('premiumStudentModal');
        const listContainer = document.getElementById('modalStudentList');
        const titleEl = document.getElementById('modalClassName');

        titleEl.innerText = className;
        listContainer.innerHTML = '';

        const currentClass = classRecords.find(c => c.id == classId);
        if (!currentClass || !currentClass.schedules) return;

        // Get unique students
        const studentsSet = new Map();
        currentClass.schedules.forEach(s => {
            if (s.student && !studentsSet.has(s.student.id)) {
                studentsSet.set(s.student.id, {
                    ...s.student,
                    day: s.day,
                    time: s.time
                });
            }
        });

        const studentList = Array.from(studentsSet.values());

        if (studentList.length === 0) {
            listContainer.innerHTML = '<p class=\"empty-state-text\">No students assigned to this class.</p>';
        } else {
            studentList.forEach(student => {
                const isActive = student.is_active;
                const initial = student.name.charAt(0).toUpperCase();
                
                const cardHtml = `
                    <div class=\"student-premium-item\">
                        <div class=\"student-row-top\">
                            <div class=\"student-avatar-box\">
                                <div class=\"avatar-circle\">${initial}</div>
                                <div>
                                    <span class=\"student-name-text\">${student.name}</span><br>
                                    <span class=\"student-status-badge ${isActive ? 'active' : ''}\">
                                        ${isActive ? 'Active' : 'Expired'}
                                    </span>
                                </div>
                            </div>
                            <a href=\"/teacher/student-progress/${student.id}\" class=\"btn-progress-premium\">
                                <span>Progress</span>
                                <i data-lucide=\"arrow-right\"></i>
                            </a>
                        </div>
                        <div class=\"student-row-meta\">
                            <div class=\"meta-item\">
                                <i data-lucide=\"calendar\"></i>
                                <span>Every ${student.day || '-'}</span>
                            </div>
                            <div class=\"meta-item\">
                                <i data-lucide=\"clock\"></i>
                                <span>${student.time ? student.time.substring(0, 5) : '-'}</span>
                            </div>
                        </div>
                    </div>
                `;
                listContainer.innerHTML += cardHtml;
            });
        }

        modal.classList.add('active');
        if (window.lucide) window.lucide.createIcons();
    }

    function hideStudentModal() {
        document.getElementById('premiumStudentModal').classList.remove('active');
    }

    // Close on click outside
    document.getElementById('premiumStudentModal').addEventListener('click', function(e) {
        if (e.target === this) hideStudentModal();
    });
</script>
@endpush
@endsection
