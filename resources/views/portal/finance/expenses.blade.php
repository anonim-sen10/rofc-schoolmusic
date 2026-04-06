@php $menuItems=[['label'=>'Dashboard','url'=>route('finance.dashboard')],['label'=>'Invoices','url'=>route('finance.invoices.index')],['label'=>'Payments','url'=>route('finance.payments.index')],['label'=>'Expenses','url'=>route('finance.expenses.index')],['label'=>'Teacher Salary','url'=>route('finance.teacher-salary.index')],['label'=>'Financial Reports','url'=>route('finance.reports.index')],['label'=>'Transactions','url'=>route('finance.transactions.index')]]; $panelTitle='Finance Dashboard'; $homeRoute=route('finance.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Expenses')
@section('page-title','Expenses Management')
@section('content')
<div class="split-grid"><section class="card"><h3>Input Expense</h3><form class="module-form" method="POST" action="{{ route('finance.expenses.store') }}">@csrf
<label>Category<input type="text" name="category" required></label>
<label>Title<input type="text" name="title" required></label>
<label>Amount<input type="number" step="0.01" name="amount" required></label>
<label>Date<input type="date" name="expense_date"></label>
<label>Note<textarea name="note"></textarea></label>
<button type="submit">Simpan Expense</button></form></section>
<section class="card"><h3>Daftar Expense</h3><div class="table-wrap"><table><thead><tr><th>Date</th><th>Category</th><th>Title</th><th>Amount</th></tr></thead><tbody>@foreach($expenses as $expense)<tr><td>{{ optional($expense->expense_date)->format('Y-m-d') }}</td><td>{{ $expense->category }}</td><td>{{ $expense->title }}</td><td>{{ $expense->amount }}</td></tr>@endforeach</tbody></table></div></section></div>
@endsection