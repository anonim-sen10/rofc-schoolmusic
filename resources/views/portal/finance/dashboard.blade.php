@php
$menuItems = [
    ['label' => 'Dashboard', 'url' => route('finance.dashboard')],
    ['label' => 'Invoices', 'url' => route('finance.invoices.index')],
    ['label' => 'Payments', 'url' => route('finance.payments.index')],
    ['label' => 'Expenses', 'url' => route('finance.expenses.index')],
    ['label' => 'Teacher Salary', 'url' => route('finance.teacher-salary.index')],
    ['label' => 'Financial Reports', 'url' => route('finance.reports.index')],
    ['label' => 'Transactions', 'url' => route('finance.transactions.index')],
];
$panelTitle = 'Finance Dashboard';
$homeRoute = route('finance.dashboard');
@endphp
@extends('portal.layouts.app')
@section('title', 'Finance Dashboard')
@section('page-title', 'Finance Overview')
@section('content')
<section class="stats-grid">
    <article class="card stat"><p>Total Invoices</p><h2>{{ $invoiceCount }}</h2></article>
    <article class="card stat"><p>Total Paid</p><h2>Rp{{ number_format($paymentTotal,0,',','.') }}</h2></article>
    <article class="card stat"><p>Total Expense</p><h2>Rp{{ number_format($expenseTotal,0,',','.') }}</h2></article>
    <article class="card stat"><p>Total Salary</p><h2>Rp{{ number_format($salaryTotal,0,',','.') }}</h2></article>
</section>
@endsection
