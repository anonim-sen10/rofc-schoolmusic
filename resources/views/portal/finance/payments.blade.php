@php $menuItems=[['label'=>'Dashboard','url'=>route('finance.dashboard')],['label'=>'Invoices','url'=>route('finance.invoices.index')],['label'=>'Payments','url'=>route('finance.payments.index')],['label'=>'Expenses','url'=>route('finance.expenses.index')],['label'=>'Teacher Salary','url'=>route('finance.teacher-salary.index')],['label'=>'Financial Reports','url'=>route('finance.reports.index')],['label'=>'Transactions','url'=>route('finance.transactions.index')]]; $panelTitle='Finance Dashboard'; $homeRoute=route('finance.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Payments')
@section('page-title','Payment Posting')
@section('content')
<div class="split-grid"><section class="card"><h3>Input Pembayaran</h3><form class="module-form" method="POST" action="{{ route('finance.payments.store') }}">@csrf
<label>Student<select name="student_id"><option value="">-</option>@foreach($students as $student)<option value="{{ $student->id }}">{{ $student->name }}</option>@endforeach</select></label>
<label>Invoice<select name="invoice_id"><option value="">-</option>@foreach($invoices as $invoice)<option value="{{ $invoice->id }}">{{ $invoice->invoice_number }}</option>@endforeach</select></label>
<label>Amount<input type="number" step="0.01" name="amount" required></label>
<label>Paid At<input type="date" name="paid_at"></label>
<label>Method<input type="text" name="method"></label>
<label>Status<select name="status"><option value="paid">paid</option><option value="pending">pending</option><option value="failed">failed</option></select></label>
<button type="submit">Posting Payment</button></form></section>
<section class="card"><h3>Riwayat Payment</h3><div class="table-wrap"><table><thead><tr><th>Student</th><th>Invoice</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($payments as $payment)<tr><td>{{ $payment->student?->name ?? '-' }}</td><td>{{ $payment->invoice?->invoice_number ?? '-' }}</td><td>{{ $payment->amount }}</td><td>{{ $payment->status }}</td></tr>@endforeach</tbody></table></div></section></div>
@endsection