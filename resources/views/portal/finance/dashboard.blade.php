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
@section('page-subtitle', 'ROFC Private Music Management Information System - Finance Operations')
@section('content')
<section class="kpi-grid" data-searchable>
    <x-ui.card title="Total Invoices">
        <div class="kpi-row">
            <div class="kpi-value">{{ $invoiceCount }}</div>
            <span class="kpi-icon"><i data-lucide="receipt"></i></span>
        </div>
    </x-ui.card>
    <x-ui.card title="Total Paid">
        <div class="kpi-row">
            <div class="kpi-value">Rp{{ number_format($paymentTotal, 0, ',', '.') }}</div>
            <span class="kpi-icon"><i data-lucide="wallet"></i></span>
        </div>
    </x-ui.card>
    <x-ui.card title="Total Expense">
        <div class="kpi-row">
            <div class="kpi-value">Rp{{ number_format($expenseTotal, 0, ',', '.') }}</div>
            <span class="kpi-icon"><i data-lucide="hand-coins"></i></span>
        </div>
    </x-ui.card>
    <x-ui.card title="Net Balance">
        <div class="kpi-row">
            <div class="kpi-value">Rp{{ number_format($netBalance, 0, ',', '.') }}</div>
            <span class="kpi-icon"><i data-lucide="scale"></i></span>
        </div>
    </x-ui.card>
</section>

<section class="split-grid-sa" data-searchable>
    <x-ui.card title="Invoice Health" subtitle="Status invoice saat ini">
        <ul class="insight-list">
            <li>
                <span><i data-lucide="clock-3"></i>Pending / Unpaid</span>
                <x-ui.badge type="warning">{{ $pendingInvoiceCount }}</x-ui.badge>
            </li>
            <li>
                <span><i data-lucide="badge-check"></i>Paid Invoices</span>
                <x-ui.badge type="success">{{ $paidInvoiceCount }}</x-ui.badge>
            </li>
            <li>
                <span><i data-lucide="banknote"></i>Teacher Salary</span>
                <strong>Rp{{ number_format($salaryTotal, 0, ',', '.') }}</strong>
            </li>
        </ul>
    </x-ui.card>

    <x-ui.card title="Recent Payments" subtitle="Transaksi pembayaran terbaru">
        @if ($recentPayments->isNotEmpty())
            <ul class="insight-list">
                @foreach ($recentPayments as $payment)
                    <li>
                        <span><i data-lucide="arrow-down-circle"></i>{{ $payment->student?->name ?? 'General Payment' }}</span>
                        <strong>Rp{{ number_format($payment->amount, 0, ',', '.') }}</strong>
                    </li>
                @endforeach
            </ul>
        @else
            <x-ui.empty-state title="No payments yet" description="Transaksi pembayaran akan tampil di sini setelah data dibuat." icon="wallet" />
        @endif
    </x-ui.card>
</section>
@endsection
