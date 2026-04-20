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
@section('title', 'Create Payment')
@section('page-title', 'Create Payment')
@section('content')
<section class="card" data-searchable>
    <h3>Form Tambah Pembayaran</h3>
    <form class="module-form" method="POST" action="{{ route('finance.payments.store') }}">
        @csrf
        <label>Student
            <select name="student_id" required>
                <option value="">Pilih student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </label>
        <label>Class
            <select name="class_id">
                <option value="">Tanpa class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </label>
        <label>Amount
            <input type="number" step="0.01" min="0" name="amount" required>
        </label>
        <label>Status
            <select name="status" required>
                <option value="paid">paid</option>
                <option value="pending">pending</option>
            </select>
        </label>
        <div class="form-actions">
            <button type="submit">Simpan Pembayaran</button>
            <a href="{{ route('finance.payments.index') }}" class="ghost-btn">Kembali</a>
        </div>
    </form>
</section>
@endsection
