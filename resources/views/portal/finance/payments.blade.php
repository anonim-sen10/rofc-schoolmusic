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
@section('title', 'Payments')
@section('page-title', 'Payment Posting')
@section('content')
<section class="stats-grid" data-searchable>
	<article class="card stat">
		<p>Total Invoice</p>
		<h2>{{ $totalInvoice }}</h2>
	</article>
	<article class="card stat">
		<p>Pembayaran Berhasil</p>
		<h2>Rp{{ number_format($successfulPayments, 0, ',', '.') }}</h2>
	</article>
</section>

<div class="split-grid" data-searchable>
	<section class="card">
		<h3>Tambah Pembayaran</h3>
		<form class="module-form" method="POST" action="{{ route('finance.payments.store') }}">
			@csrf
			<label>Student
				<select name="student_id" required>
					<option value="">Pilih student</option>
					@foreach($students as $student)
						<option value="{{ $student->id }}" @selected((string) old('student_id') === (string) $student->id)>{{ $student->name }}</option>
					@endforeach
				</select>
			</label>
			<label>Class
				<select name="class_id">
					<option value="">Tanpa class</option>
					@foreach($classes as $class)
						<option value="{{ $class->id }}" @selected((string) old('class_id') === (string) $class->id)>{{ $class->name }}</option>
					@endforeach
				</select>
			</label>
			<label>Amount
				<input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" required>
			</label>
			<label>Status
				<select name="status" required>
					<option value="paid" @selected(old('status', 'paid') === 'paid')>paid</option>
					<option value="pending" @selected(old('status') === 'pending')>pending</option>
				</select>
			</label>
			<button type="submit">Simpan Pembayaran</button>
		</form>
	</section>

	<section class="card">
		<h3>Daftar Pembayaran</h3>
		<div class="table-wrap">
			<table>
				<thead>
					<tr>
						<th>Student</th>
						<th>Class</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Tanggal</th>
					</tr>
				</thead>
				<tbody>
					@forelse($payments as $payment)
						<tr>
							<td>{{ $payment->student?->name ?? '-' }}</td>
							<td>{{ $payment->musicClass?->name ?? '-' }}</td>
							<td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
							<td>
								<x-ui.badge :type="$payment->status === 'paid' ? 'success' : 'warning'">
									{{ strtoupper($payment->status) }}
								</x-ui.badge>
							</td>
							<td>{{ optional($payment->created_at)->format('Y-m-d H:i') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="5">Belum ada data pembayaran.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</section>
</div>
@endsection