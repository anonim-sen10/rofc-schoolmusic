<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\MusicClass;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        return view('portal.finance.payments', [
            'payments' => Payment::query()->with(['student', 'musicClass'])->latest()->get(),
            'students' => Student::query()->orderBy('name')->get(['id', 'name']),
            'classes' => MusicClass::query()->orderBy('name')->get(['id', 'name']),
            'totalInvoice' => Payment::query()->count(),
            'successfulPayments' => (float) Payment::query()->where('status', 'paid')->sum('amount'),
        ]);
    }

    public function create(): View
    {
        return view('portal.finance.payment-create', [
            'students' => Student::query()->orderBy('name')->get(['id', 'name']),
            'classes' => MusicClass::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:paid,pending'],
        ]);

        Payment::query()->create([
            'student_id' => $data['student_id'],
            'class_id' => $data['class_id'] ?? null,
            'amount' => $data['amount'],
            'status' => $data['status'],
            'paid_at' => $data['status'] === 'paid' ? now()->toDateString() : null,
            'method' => 'manual',
        ]);

        return back()->with('success', 'Pembayaran berhasil ditambahkan.');
    }
}
