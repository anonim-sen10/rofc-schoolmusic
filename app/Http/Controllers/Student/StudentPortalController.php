<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MusicClass;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    private function studentFromUser(int $userId, string $name, string $email): Student
    {
        return Student::firstOrCreate(
            ['user_id' => $userId],
            ['name' => $name, 'email' => $email, 'is_active' => true]
        );
    }

    public function dashboard(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.dashboard', [
            'student' => $student,
            'classCount' => $student->classes()->count(),
            'payments' => Payment::where('student_id', $student->id)->latest()->take(5)->get(),
            'progress' => StudentProgress::where('student_id', $student->id)->latest()->take(5)->get(),
        ]);
    }

    public function myClass(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.my-class', [
            'student' => $student,
            'classes' => $student->classes()->with('teacher')->get(),
        ]);
    }

    public function schedule(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.schedule', [
            'classes' => $student->classes()->with('teacher')->get(),
        ]);
    }

    public function payment(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.payment', [
            'student' => $student,
            'payments' => Payment::where('student_id', $student->id)->latest()->get(),
        ]);
    }

    public function paymentStatus(Request $request): JsonResponse
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        $latest = Payment::where('student_id', $student->id)->latest()->first();

        return response()->json([
            'latest_status' => $latest?->status ?? 'no-payment',
            'latest_amount' => $latest?->amount,
            'latest_date' => optional($latest?->paid_at)->format('Y-m-d'),
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    public function progress(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.progress', [
            'records' => StudentProgress::where('student_id', $student->id)->latest()->get(),
        ]);
    }

    public function materials(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);
        $classIds = $student->classes()->pluck('classes.id');

        return view('portal.student.materials', [
            'materials' => Material::whereIn('class_id', $classIds)->orWhereNull('class_id')->latest()->get(),
        ]);
    }

    public function profile(Request $request): View
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        return view('portal.student.profile', [
            'student' => $student,
        ]);
    }

    public function updateProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $student = $this->studentFromUser($request->user()->id, $request->user()->name, $request->user()->email);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'age' => ['nullable', 'integer', 'min:4', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
        ]);

        $student->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
