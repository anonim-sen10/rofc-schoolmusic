@php $menuItems=[['label'=>'Dashboard','url'=>route('finance.dashboard')],['label'=>'Invoices','url'=>route('finance.invoices.index')],['label'=>'Payments','url'=>route('finance.payments.index')],['label'=>'Expenses','url'=>route('finance.expenses.index')],['label'=>'Teacher Salary','url'=>route('finance.teacher-salary.index')],['label'=>'Financial Reports','url'=>route('finance.reports.index')],['label'=>'Transactions','url'=>route('finance.transactions.index')]]; $panelTitle='Finance Dashboard'; $homeRoute=route('finance.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Financial Reports')
@section('page-title','Financial Reports')
@section('content')
<section class="stats-grid">
<article class="card stat"><p>Income</p><h2>Rp{{ number_format($income,0,',','.') }}</h2></article>
<article class="card stat"><p>Expense</p><h2>Rp{{ number_format($expense,0,',','.') }}</h2></article>
<article class="card stat"><p>Salary</p><h2>Rp{{ number_format($salary,0,',','.') }}</h2></article>
<article class="card stat"><p>Net</p><h2>Rp{{ number_format($net,0,',','.') }}</h2></article>
</section>
<section class="card"><h3>Export</h3><p><a href="{{ route('finance.reports.export.csv') }}">Download CSV</a> | <a href="{{ route('finance.reports.export.pdf') }}" target="_blank">Print PDF View</a></p><div class="table-wrap"><table><thead><tr><th>Date</th><th>Student</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($transactions as $row)<tr><td>{{ optional($row->paid_at)->format('Y-m-d') }}</td><td>{{ $row->student?->name }}</td><td>{{ $row->amount }}</td><td>{{ $row->status }}</td></tr>@endforeach</tbody></table></div></section>
@endsection