@php $menuItems=[['label'=>'Dashboard','url'=>route('finance.dashboard')],['label'=>'Invoices','url'=>route('finance.invoices.index')],['label'=>'Payments','url'=>route('finance.payments.index')],['label'=>'Expenses','url'=>route('finance.expenses.index')],['label'=>'Teacher Salary','url'=>route('finance.teacher-salary.index')],['label'=>'Financial Reports','url'=>route('finance.reports.index')],['label'=>'Transactions','url'=>route('finance.transactions.index')]]; $panelTitle='Finance Dashboard'; $homeRoute=route('finance.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Invoices')
@section('page-title','Invoice Management')
@section('content')
<div class="split-grid">
<section class="card"><h3>Buat Invoice</h3><form class="module-form" method="POST" action="{{ route('finance.invoices.store') }}">@csrf
<label>Student<select name="student_id"><option value="">-</option>@foreach($students as $student)<option value="{{ $student->id }}">{{ $student->name }}</option>@endforeach</select></label>
<label>Amount<input type="number" step="0.01" name="amount" required></label>
<label>Issued At<input type="date" name="issued_at"></label>
<label>Due At<input type="date" name="due_at"></label>
<label>Status<select name="status"><option value="draft">draft</option><option value="issued">issued</option><option value="paid">paid</option><option value="overdue">overdue</option></select></label>
<button type="submit">Simpan Invoice</button></form></section>
<section class="card"><h3>Daftar Invoice</h3><div class="table-wrap"><table><thead><tr><th>No Invoice</th><th>Student</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($invoices as $invoice)<tr><td>{{ $invoice->invoice_number }}</td><td>{{ $invoice->student?->name ?? '-' }}</td><td>{{ $invoice->amount }}</td><td>{{ $invoice->status }}</td></tr>@endforeach</tbody></table></div></section>
</div>
@endsection