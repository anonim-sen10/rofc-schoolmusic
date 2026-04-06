@php $menuItems=[['label'=>'Dashboard','url'=>route('finance.dashboard')],['label'=>'Invoices','url'=>route('finance.invoices.index')],['label'=>'Payments','url'=>route('finance.payments.index')],['label'=>'Expenses','url'=>route('finance.expenses.index')],['label'=>'Teacher Salary','url'=>route('finance.teacher-salary.index')],['label'=>'Financial Reports','url'=>route('finance.reports.index')],['label'=>'Transactions','url'=>route('finance.transactions.index')]]; $panelTitle='Finance Dashboard'; $homeRoute=route('finance.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Teacher Salary')
@section('page-title','Teacher Salary Management')
@section('content')
<div class="split-grid"><section class="card"><h3>Input Salary</h3><form class="module-form" method="POST" action="{{ route('finance.teacher-salary.store') }}">@csrf
<label>Teacher<select name="teacher_id" required>@foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach</select></label>
<label>Period<input type="text" name="period" placeholder="2026-04" required></label>
<label>Base Salary<input type="number" step="0.01" name="base_salary" required></label>
<label>Bonus<input type="number" step="0.01" name="bonus"></label>
<label>Deduction<input type="number" step="0.01" name="deduction"></label>
<label>Paid At<input type="date" name="paid_at"></label>
<button type="submit">Simpan Salary</button></form></section>
<section class="card"><h3>Daftar Salary</h3><div class="table-wrap"><table><thead><tr><th>Teacher</th><th>Period</th><th>Total</th><th>Paid At</th></tr></thead><tbody>@foreach($salaries as $salary)<tr><td>{{ $salary->teacher?->name }}</td><td>{{ $salary->period }}</td><td>{{ $salary->total_paid }}</td><td>{{ optional($salary->paid_at)->format('Y-m-d') }}</td></tr>@endforeach</tbody></table></div></section></div>
@endsection