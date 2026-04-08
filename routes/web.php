<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\AcademicManagementController;
use App\Http\Controllers\Finance\FinanceManagementController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Student\StudentPortalController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Teacher\TeacherPortalController;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::view('/about', 'pages.about')->name('about');
Route::view('/programs', 'pages.programs')->name('programs');
Route::view('/teachers', 'pages.teachers')->name('teachers');
Route::view('/gallery', 'pages.gallery')->name('gallery');
Route::view('/events', 'pages.events')->name('events');
Route::view('/blog', 'pages.blog')->name('blog');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/register', 'pages.register')->name('register');

Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:120'],
        'phone' => ['required', 'string', 'max:30'],
        'email' => ['required', 'email', 'max:120'],
        'message' => ['required', 'string', 'max:1000'],
    ]);

    return back()->with('success', 'Terima kasih, pesan Anda sudah kami terima. Tim ROFC akan segera menghubungi Anda.');
})->name('contact.submit');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'full_name' => ['required', 'string', 'max:120'],
        'age' => ['required', 'integer', 'min:4', 'max:80'],
        'phone' => ['required', 'string', 'max:30'],
        'email' => ['required', 'email', 'max:120'],
        'program' => ['required', 'string', 'max:80'],
        'preferred_schedule' => ['required', 'string', 'max:80'],
        'notes' => ['nullable', 'string', 'max:1000'],
    ]);

    Registration::create([
        'full_name' => $validated['full_name'],
        'age' => $validated['age'],
        'phone' => $validated['phone'],
        'email' => $validated['email'],
        'preferred_schedule' => $validated['preferred_schedule'],
        'notes' => $validated['notes'] ?? null,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Pendaftaran Anda berhasil dikirim. Kami akan menghubungi Anda untuk proses berikutnya.');
})->name('register.submit');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/portal', [PortalController::class, 'redirectByRole'])->name('portal.redirect');
    Route::get('/portal/custom', [PortalController::class, 'customDashboard'])->name('portal.custom.dashboard');
});

Route::prefix('super-admin')->name('super-admin.')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
    Route::post('/roles', [SuperAdminController::class, 'storeRole'])->name('roles.store');
    Route::get('/{module}', [SuperAdminController::class, 'module'])
        ->whereIn('module', ['users', 'roles', 'classes', 'teachers', 'students', 'registrations', 'finance', 'reports', 'blog', 'gallery', 'events', 'testimonials', 'settings', 'logs'])
        ->name('module');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/', fn () => app(PortalController::class)->dashboard('admin'))->name('dashboard');

    Route::get('/classes', [AcademicManagementController::class, 'classes'])->name('classes.index');
    Route::post('/classes', [AcademicManagementController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{class}', [AcademicManagementController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{class}', [AcademicManagementController::class, 'destroyClass'])->name('classes.destroy');

    Route::get('/teachers', [AcademicManagementController::class, 'teachers'])->name('teachers.index');
    Route::post('/teachers', [AcademicManagementController::class, 'storeTeacher'])->name('teachers.store');
    Route::put('/teachers/{teacher}', [AcademicManagementController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [AcademicManagementController::class, 'destroyTeacher'])->name('teachers.destroy');

    Route::get('/students', [AcademicManagementController::class, 'students'])->name('students.index');
    Route::post('/students', [AcademicManagementController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{student}', [AcademicManagementController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{student}', [AcademicManagementController::class, 'destroyStudent'])->name('students.destroy');

    Route::get('/registrations', [AcademicManagementController::class, 'registrations'])->name('registrations.index');
    Route::patch('/registrations/{registration}/status', [AcademicManagementController::class, 'updateRegistrationStatus'])->name('registrations.status');

    Route::get('/{module}', fn (string $module) => app(PortalController::class)->module('admin', $module))
        ->whereIn('module', ['gallery', 'blog', 'events', 'testimonials'])
        ->name('module');
});

Route::prefix('finance')->name('finance.')->middleware(['auth', 'role:finance'])->group(function () {
    Route::get('/', [FinanceManagementController::class, 'dashboard'])->name('dashboard');
    Route::get('/invoices', [FinanceManagementController::class, 'invoices'])->name('invoices.index');
    Route::post('/invoices', [FinanceManagementController::class, 'storeInvoice'])->name('invoices.store');
    Route::get('/payments', [FinanceManagementController::class, 'payments'])->name('payments.index');
    Route::post('/payments', [FinanceManagementController::class, 'storePayment'])->name('payments.store');
    Route::get('/expenses', [FinanceManagementController::class, 'expenses'])->name('expenses.index');
    Route::post('/expenses', [FinanceManagementController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/teacher-salary', [FinanceManagementController::class, 'teacherSalaries'])->name('teacher-salary.index');
    Route::post('/teacher-salary', [FinanceManagementController::class, 'storeTeacherSalary'])->name('teacher-salary.store');
    Route::get('/reports', [FinanceManagementController::class, 'reports'])->name('reports.index');
    Route::get('/reports/export/csv', [FinanceManagementController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/export/pdf', [FinanceManagementController::class, 'exportPdfView'])->name('reports.export.pdf');
    Route::get('/transactions', [FinanceManagementController::class, 'payments'])->name('transactions.index');
});

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/', [TeacherPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [TeacherPortalController::class, 'attendance'])->name('attendance.index');
    Route::post('/attendance', [TeacherPortalController::class, 'storeAttendance'])->name('attendance.store');
    Route::post('/attendance/teacher', [TeacherPortalController::class, 'storeTeacherAttendance'])->name('attendance.teacher.store');
    Route::get('/student-progress', [TeacherPortalController::class, 'progress'])->name('student-progress.index');
    Route::post('/student-progress', [TeacherPortalController::class, 'storeProgress'])->name('student-progress.store');
    Route::get('/materials', [TeacherPortalController::class, 'materials'])->name('materials.index');
    Route::post('/materials', [TeacherPortalController::class, 'storeMaterial'])->name('materials.store');
    Route::get('/my-classes', [TeacherPortalController::class, 'attendance'])->name('my-classes.index');
    Route::get('/my-students', [TeacherPortalController::class, 'progress'])->name('my-students.index');
    Route::get('/schedule', [TeacherPortalController::class, 'attendance'])->name('schedule.index');
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
});
