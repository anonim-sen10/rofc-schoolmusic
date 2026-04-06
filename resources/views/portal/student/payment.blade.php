@php $menuItems=[['label'=>'Dashboard','url'=>route('student.dashboard')],['label'=>'My Class','url'=>route('student.my-class.index')],['label'=>'Schedule','url'=>route('student.schedule.index')],['label'=>'Payment','url'=>route('student.payment.index')],['label'=>'Progress','url'=>route('student.progress.index')],['label'=>'Materials','url'=>route('student.materials.index')],['label'=>'Profile','url'=>route('student.profile.index')],['label'=>'Events','url'=>route('student.events.index')]]; $panelTitle='Student Portal'; $homeRoute=route('student.dashboard'); @endphp
@extends('portal.layouts.app')
@section('title','Payment Status')
@section('page-title','Payment Status (Realtime)')
@section('content')
<section class="card"><p>Latest Status: <strong id="payment-latest-status">Loading...</strong></p><p id="payment-latest-detail"></p></section>
<section class="card"><div class="table-wrap"><table><thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Status</th></tr></thead><tbody>@foreach($payments as $payment)<tr><td>{{ optional($payment->paid_at)->format('Y-m-d') }}</td><td>{{ $payment->amount }}</td><td>{{ $payment->method }}</td><td>{{ $payment->status }}</td></tr>@endforeach</tbody></table></div></section>
<script>
const pollPaymentStatus = async () => {
  try {
    const response = await fetch('{{ route('student.payment.status') }}');
    const data = await response.json();
    document.getElementById('payment-latest-status').textContent = data.latest_status;
    document.getElementById('payment-latest-detail').textContent = `Amount: ${data.latest_amount ?? '-'} | Date: ${data.latest_date ?? '-'} | Updated: ${data.updated_at}`;
  } catch (e) {
    document.getElementById('payment-latest-status').textContent = 'unavailable';
  }
};
pollPaymentStatus();
setInterval(pollPaymentStatus, 15000);
</script>
@endsection