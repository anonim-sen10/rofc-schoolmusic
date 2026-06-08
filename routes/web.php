<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AcademicManagementController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\FinanceManagementController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Student\StudentPortalController;
use App\Http\Controllers\SuperAdmin\ScheduleController as SuperAdminScheduleController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Teacher\TeacherPortalController;
use App\Http\Controllers\Teacher\TeacherProgressController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitemapController;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

// TEMPORARY: Run migration from browser
Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Migration successful: " . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        return "Migration failed: " . $e->getMessage();
    }
});

// TEMPORARY: Clear cache from browser
Route::get('/clear-cache', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        return "Cache cleared successfully!";
    } catch (\Exception $e) {
        return "Failed to clear cache: " . $e->getMessage();
    }
});

// TEMPORARY: Sync existing students with registration data
Route::get('/sync-students', function () {
    try {
        $students = \App\Models\Student::all();
        $count = 0;
        foreach ($students as $student) {
            $registration = \App\Models\Registration::where('email', $student->email)->first();
            if ($registration) {
                $student->update([
                    'nama_panggilan' => $student->nama_panggilan ?: $registration->nama_panggilan,
                    'jenis_kelamin' => $student->jenis_kelamin ?: $registration->jenis_kelamin,
                    'tempat_lahir' => $student->tempat_lahir ?: $registration->tempat_lahir,
                    'tanggal_lahir' => $student->tanggal_lahir ?: $registration->tanggal_lahir,
                    'kewarganegaraan' => $student->kewarganegaraan ?: $registration->kewarganegaraan,
                    'address' => $student->address ?: ($registration->alamat ?: $registration->address),
                    'nama_ortu' => $student->nama_ortu ?: $registration->nama_ortu,
                    'pekerjaan_ortu' => $student->pekerjaan_ortu ?: $registration->pekerjaan_ortu,
                    'no_hp_ortu' => $student->no_hp_ortu ?: $registration->no_hp_ortu,
                    'email_ortu' => $student->email_ortu ?: $registration->email_ortu,
                    'program_tambahan' => $student->program_tambahan ?: $registration->program_tambahan,
                    'pengalaman' => $student->pengalaman ?? $registration->pengalaman,
                    'deskripsi_pengalaman' => $student->deskripsi_pengalaman ?: $registration->deskripsi_pengalaman,
                    'favorite_song' => $student->favorite_song ?: $registration->favorite_song,
                ]);
                $count++;
            }
        }
        return "Successfully synced $count students.";
    } catch (\Exception $e) {
        return "Sync failed: " . $e->getMessage();
    }
});

Route::get('/debug-db', function () {
    try {
        $student = \App\Models\Student::first();
        return [
            'columns' => \Illuminate\Support\Facades\Schema::getColumnListing('students'),
            'student' => $student ? $student->toArray() : null,
            'is_active_type' => $student ? gettype($student->is_active) : null,
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
});

Route::get('/view-logs', function () {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) {
        return "Log file does not exist.";
    }
    $lines = file($path);
    $lastLines = array_slice($lines, -800);
    return response(implode("", $lastLines), 200, ['Content-Type' => 'text/plain']);
});

Route::view('/about', 'pages.about')->name('about');
Route::view('/programs', 'pages.programs')->name('programs');
Route::view('/teachers', 'pages.teachers')->name('teachers');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/events', 'pages.events')->name('events');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/contact', 'pages.contact')->name('contact');
Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::get('/schedules/by-class/{class_id}', [RegistrationController::class, 'getSchedulesByClass'])->name('register.schedules.by-class');
Route::get('/get-available-schedules/{class_id}/{day}', [RegistrationController::class, 'getAvailableSchedules'])->name('register.schedules.available');
Route::get('/sitemap.xml', [SitemapController::class, 'index']);


Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'phone' => ['required', 'string', 'max:30'],
        'email' => ['required', 'email', 'max:120'],
        'message' => ['required', 'string', 'max:1000'],
    ]);

    return back()->with('success', 'Terima kasih, pesan Anda sudah kami terima. Tim ROFC akan segera menghubungi Anda.');
})->name('contact.submit');

Route::post('/register', [RegistrationController::class, 'store'])->name('register.submit');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/portal', [PortalController::class, 'redirectByRole'])->name('portal.redirect');
    Route::get('/portal/custom', [PortalController::class, 'customDashboard'])->name('portal.custom.dashboard');
    Route::get('/stop-impersonate', [SuperAdminController::class, 'stopImpersonate'])->name('stop-impersonate');
    Route::get('/session-keep-alive', function () {
        return response()->json(['status' => 'alive', 'time' => now()->toDateTimeString()]);
    })->name('session.keep-alive');
});

Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::post('/classes', [SuperAdminController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{class}', [SuperAdminController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{class}', [SuperAdminController::class, 'destroyClass'])->name('classes.destroy');
    Route::post('/students', [SuperAdminController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{student}', [SuperAdminController::class, 'updateStudent'])->name('students.update');
    Route::post('/students/{student}/extend', [SuperAdminController::class, 'extendStudent'])->name('students.extend');
    Route::delete('/students/{student}', [SuperAdminController::class, 'destroyStudent'])->name('students.destroy');
    Route::post('/registrations', [SuperAdminController::class, 'storeRegistration'])->name('registrations.store');
    Route::post('/registrations/{id}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve');
    Route::put('/registrations/{registration}', [SuperAdminController::class, 'updateRegistration'])->name('registrations.update');
    Route::delete('/registrations/{registration}', [SuperAdminController::class, 'destroyRegistration'])->name('registrations.destroy');
    Route::post('/content/{module}', [SuperAdminController::class, 'storeContent'])->whereIn('module', ['blog', 'gallery', 'events', 'testimonials', 'settings'])->name('content.store');
    Route::put('/content/{module}/{id}', [SuperAdminController::class, 'updateContent'])->whereIn('module', ['blog', 'gallery', 'events', 'testimonials', 'settings'])->name('content.update');
    Route::delete('/content/{module}/{id}', [SuperAdminController::class, 'destroyContent'])->whereIn('module', ['blog', 'gallery', 'events', 'testimonials', 'settings'])->name('content.destroy');
    Route::delete('/logs/{id}', [SuperAdminController::class, 'destroyLog'])->name('logs.destroy');
    Route::post('/teachers', [SuperAdminController::class, 'storeTeacherAccount'])->name('teachers.store');
    Route::get('/teachers/{teacher}/detail', [SuperAdminController::class, 'showTeacher'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [SuperAdminController::class, 'editTeacher'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [SuperAdminController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [SuperAdminController::class, 'destroyTeacher'])->name('teachers.destroy');
    Route::post('/schedule', [SuperAdminScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{schedule}', [SuperAdminScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{schedule}', [SuperAdminScheduleController::class, 'destroy'])->name('schedule.destroy');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/users/{user}/detail', [SuperAdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [SuperAdminController::class, 'editUser'])->name('users.edit');
    Route::get('/users/{user}/impersonate', [SuperAdminController::class, 'impersonate'])->name('users.impersonate');
    Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/roles', [SuperAdminController::class, 'storeRole'])->name('roles.store');
    Route::get('/attendance', [AcademicManagementController::class, 'attendance'])->name('attendance.index');
    Route::get('/attendance/export', [AcademicManagementController::class, 'exportAttendance'])->name('attendance.export');
    Route::put('/attendance/{id}', [AcademicManagementController::class, 'updateAttendance'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AcademicManagementController::class, 'destroyAttendance'])->name('attendance.destroy');
    Route::get('/{module}', function (string $module) {
        if ($module === 'schedule') {
            return app(SuperAdminScheduleController::class)->index();
        }

        if ($module === 'attendance') {
            return app(AcademicManagementController::class)->attendance(request());
        }

        return app(SuperAdminController::class)->module($module);
    })
        ->whereIn('module', ['users', 'roles', 'classes', 'teachers', 'schedule', 'students', 'registrations', 'reschedule', 'finance', 'reports', 'blog', 'gallery', 'events', 'testimonials', 'settings', 'logs', 'attendance'])
        ->name('module');
    // Reschedule management
    Route::match(['get', 'post'], '/reschedule/{id}/approve', [\App\Http\Controllers\Admin\RescheduleManagementController::class, 'approve'])->name('reschedule.approve');
    Route::match(['get', 'post'], '/reschedule/{id}/reject', [\App\Http\Controllers\Admin\RescheduleManagementController::class, 'reject'])->name('reschedule.reject');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/classes', [SuperAdminController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{class}', [SuperAdminController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{class}', [SuperAdminController::class, 'destroyClass'])->name('classes.destroy');
    Route::post('/students', [SuperAdminController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{student}', [SuperAdminController::class, 'updateStudent'])->name('students.update');
    Route::post('/students/{student}/extend', [SuperAdminController::class, 'extendStudent'])->name('students.extend');
    Route::delete('/students/{student}', [SuperAdminController::class, 'destroyStudent'])->name('students.destroy');
    Route::post('/registrations', [SuperAdminController::class, 'storeRegistration'])->name('registrations.store');
    Route::post('/registrations/{id}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve');
    Route::put('/registrations/{registration}', [SuperAdminController::class, 'updateRegistration'])->name('registrations.update');
    Route::delete('/registrations/{registration}', [SuperAdminController::class, 'destroyRegistration'])->name('registrations.destroy');
    Route::post('/content/{module}', [SuperAdminController::class, 'storeContent'])->whereIn('module', ['blog', 'gallery', 'events', 'testimonials'])->name('content.store');
    Route::put('/content/{module}/{id}', [SuperAdminController::class, 'updateContent'])->whereIn('module', ['blog', 'gallery', 'events', 'testimonials'])->name('content.update');
    Route::delete('/content/{module}/{id}', [SuperAdminController::class, 'destroyContent'])->whereIn('module', ['blog', 'gallery', 'events', 'testimonials'])->name('content.destroy');
    Route::post('/teachers', [SuperAdminController::class, 'storeTeacherAccount'])->name('teachers.store');
    Route::get('/teachers/{teacher}/detail', [SuperAdminController::class, 'showTeacher'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [SuperAdminController::class, 'editTeacher'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [SuperAdminController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [SuperAdminController::class, 'destroyTeacher'])->name('teachers.destroy');
    Route::post('/schedule', [SuperAdminScheduleController::class, 'store'])->name('schedule.store');
    Route::put('/schedule/{schedule}', [SuperAdminScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/schedule/{schedule}', [SuperAdminScheduleController::class, 'destroy'])->name('schedule.destroy');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/attendance', [AcademicManagementController::class, 'attendance'])->name('attendance.index');
    Route::get('/attendance/export', [AcademicManagementController::class, 'exportAttendance'])->name('attendance.export');
    Route::put('/attendance/{id}', [AcademicManagementController::class, 'updateAttendance'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AcademicManagementController::class, 'destroyAttendance'])->name('attendance.destroy');

    Route::get('/{module}', function (string $module) {
        if ($module === 'schedule') {
            return app(SuperAdminScheduleController::class)->index();
        }

        if ($module === 'attendance') {
            return app(AcademicManagementController::class)->attendance(request());
        }

        if (in_array($module, ['users', 'roles', 'settings', 'logs'])) {
            abort(403, 'Unauthorized action.');
        }

        return app(SuperAdminController::class)->module($module);
    })
        ->whereIn('module', ['classes', 'teachers', 'schedule', 'students', 'registrations', 'reschedule', 'finance', 'reports', 'blog', 'gallery', 'events', 'testimonials', 'attendance'])
        ->name('module');

    // Reschedule management
    Route::match(['get', 'post'], '/reschedule/{id}/approve', [\App\Http\Controllers\Admin\RescheduleManagementController::class, 'approve'])->name('reschedule.approve');
    Route::match(['get', 'post'], '/reschedule/{id}/reject', [\App\Http\Controllers\Admin\RescheduleManagementController::class, 'reject'])->name('reschedule.reject');
});

Route::prefix('finance')->name('finance.')->middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/', [FinanceManagementController::class, 'dashboard'])->name('dashboard');
    Route::get('/invoices', [FinanceManagementController::class, 'invoices'])->name('invoices.index');
    Route::post('/invoices', [FinanceManagementController::class, 'storeInvoice'])->name('invoices.store');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/expenses', [FinanceManagementController::class, 'expenses'])->name('expenses.index');
    Route::post('/expenses', [FinanceManagementController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/teacher-salary', [FinanceManagementController::class, 'teacherSalaries'])->name('teacher-salary.index');
    Route::post('/teacher-salary', [FinanceManagementController::class, 'storeTeacherSalary'])->name('teacher-salary.store');
    Route::get('/reports', [FinanceManagementController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export/csv', [FinanceManagementController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/export/pdf', [FinanceManagementController::class, 'exportPdfView'])->name('reports.export.pdf');
    Route::get('/transactions', [PaymentController::class, 'index'])->name('transactions.index');
});

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [TeacherPortalController::class, 'attendance'])->name('attendance.index');
    Route::post('/attendance', [TeacherPortalController::class, 'storeAttendance'])->name('attendance.store');
    Route::post('/attendance/teacher', [TeacherPortalController::class, 'storeTeacherAttendance'])->name('attendance.teacher.store');
    Route::get('/student-progress', [TeacherPortalController::class, 'progress'])->name('student-progress.index');
    Route::get('/student-progress/{student_id}', [TeacherProgressController::class, 'show'])->name('student-progress.input');
    Route::post('/student-progress', [TeacherProgressController::class, 'store'])->name('student-progress.store');
    Route::get('/materials', [TeacherPortalController::class, 'materials'])->name('materials.index');
    Route::post('/materials', [TeacherPortalController::class, 'storeMaterial'])->name('materials.store');
Route::get('/my-classes', [TeacherPortalController::class, 'myClasses'])->name('my-classes.index');
    Route::get('/my-students', [TeacherStudentController::class, 'index'])->name('my-students.index');
    Route::get('/schedule', [TeacherPortalController::class, 'schedule'])->name('schedule.index');
    Route::post('/schedule/{class}/respond', [TeacherPortalController::class, 'respondSchedule'])->name('schedule.respond');
    Route::post('/schedule/attendance', [TeacherPortalController::class, 'storeScheduleAttendance'])->name('schedule.attendance.store');
    Route::get('/profile', [TeacherPortalController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [TeacherPortalController::class, 'updateProfile'])->name('profile.update');
    
    // Reschedule
    Route::get('/schedule/available-slots', [TeacherPortalController::class, 'availableSlots'])->name('schedule.available-slots');
    Route::post('/schedule/reschedule', [TeacherPortalController::class, 'requestReschedule'])->name('schedule.reschedule.request');
});

Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/', [StudentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-class', [StudentPortalController::class, 'myClass'])->name('my-class.index');
    Route::get('/schedule', [StudentPortalController::class, 'schedule'])->name('schedule.index');
    Route::get('/payment', [StudentPortalController::class, 'payment'])->name('payment.index');
    Route::get('/payment/status', [StudentPortalController::class, 'paymentStatus'])->name('payment.status');
    Route::get('/progress', [StudentPortalController::class, 'progress'])->name('progress.index');
    Route::get('/materials', [StudentPortalController::class, 'materials'])->name('materials.index');
    Route::get('/profile', [StudentPortalController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [StudentPortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/events', fn () => view('portal.student.events'))->name('events.index');
    
    // Reschedule
    Route::get('/schedule/available-slots', [StudentPortalController::class, 'availableSlots'])->name('schedule.available-slots');
    Route::post('/schedule/reschedule', [StudentPortalController::class, 'requestReschedule'])->name('schedule.reschedule.request');
});
