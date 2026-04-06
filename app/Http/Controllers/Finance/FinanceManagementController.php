<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Services\Finance\InvoiceNumberService;
use App\Services\Finance\PayrollService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FinanceManagementController extends Controller
{
    public function dashboard(): View
    {
        return view('portal.finance.dashboard', [
            'invoiceCount' => Invoice::count(),
            'paymentTotal' => Payment::where('status', 'paid')->sum('amount'),
            'expenseTotal' => Expense::sum('amount'),
            'salaryTotal' => TeacherSalary::sum('total_paid'),
        ]);
    }

    public function invoices(): View
    {
        return view('portal.finance.invoices', [
            'invoices' => Invoice::with('student')->latest()->get(),
            'students' => Student::orderBy('name')->get(),
        ]);
    }

    public function storeInvoice(Request $request, InvoiceNumberService $invoiceNumberService): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['nullable', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'issued_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
            'status' => ['required', 'in:draft,issued,paid,overdue'],
        ]);

        $data['invoice_number'] = $invoiceNumberService->generate();
        Invoice::create($data);

        return back()->with('success', 'Invoice berhasil dibuat.');
    }

    public function payments(): View
    {
        return view('portal.finance.payments', [
            'payments' => Payment::with(['student', 'invoice'])->latest()->get(),
            'students' => Student::orderBy('name')->get(),
            'invoices' => Invoice::orderByDesc('id')->take(100)->get(),
        ]);
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['nullable', 'exists:students,id'],
            'invoice_id' => ['nullable', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'method' => ['nullable', 'string', 'max:40'],
            'status' => ['required', 'in:pending,paid,failed'],
        ]);

        $payment = Payment::create($data);

        if ($payment->invoice && $payment->status === 'paid') {
            $invoice = $payment->invoice;
            $totalPaid = $invoice->payments()->where('status', 'paid')->sum('amount');
            if ($totalPaid >= $invoice->amount) {
                $invoice->update(['status' => 'paid']);
            }
        }

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function expenses(): View
    {
        return view('portal.finance.expenses', [
            'expenses' => Expense::latest('expense_date')->get(),
        ]);
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        Expense::create($data);

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function teacherSalaries(): View
    {
        return view('portal.finance.teacher-salaries', [
            'salaries' => TeacherSalary::with('teacher')->latest()->get(),
            'teachers' => Teacher::orderBy('name')->get(),
        ]);
    }

    public function storeTeacherSalary(Request $request, PayrollService $payrollService): RedirectResponse
    {
        $data = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'period' => ['required', 'string', 'max:20'],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'deduction' => ['nullable', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $data['bonus'] = (float) ($data['bonus'] ?? 0);
        $data['deduction'] = (float) ($data['deduction'] ?? 0);
        $data['total_paid'] = $payrollService->calculateTotal((float) $data['base_salary'], $data['bonus'], $data['deduction']);

        TeacherSalary::create($data);

        return back()->with('success', 'Data gaji guru berhasil disimpan.');
    }

    public function reports(): View
    {
        $income = Payment::where('status', 'paid')->sum('amount');
        $expense = Expense::sum('amount');
        $salary = TeacherSalary::sum('total_paid');

        return view('portal.finance.reports', [
            'income' => $income,
            'expense' => $expense,
            'salary' => $salary,
            'net' => $income - $expense - $salary,
            'transactions' => Payment::with('student')->latest()->take(15)->get(),
        ]);
    }

    public function exportCsv(): Response
    {
        $rows = Payment::with('student')->latest()->get();

        $csv = "Date,Student,Amount,Method,Status\n";
        foreach ($rows as $row) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s\n",
                optional($row->paid_at)->format('Y-m-d') ?? '',
                str_replace(',', ' ', $row->student?->name ?? '-'),
                $row->amount,
                str_replace(',', ' ', $row->method ?? '-'),
                $row->status
            );
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="financial-report.csv"',
        ]);
    }

    public function exportPdfView(): View
    {
        return view('portal.finance.report-pdf', [
            'payments' => Payment::with('student')->latest()->take(50)->get(),
            'expenses' => Expense::latest('expense_date')->take(50)->get(),
            'salaries' => TeacherSalary::with('teacher')->latest()->take(50)->get(),
        ]);
    }
}
