@extends('layouts.tabler')
@section('title')
    {{ __('Payments History') }}
@endsection
@section('content')
<div class="container">
    <h1>{{ __('Payment History for') }} {{ $debt->customer->name ?? 'Personal Debt' }}</h1>
    @if($payments->isEmpty())
        <p>No payment history found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Amount Paid') }}</th>
                    <th>{{ __('Account Holder') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($payment->paid_at)->toFormattedDateString() }}</td>
                        <td> {{ auth()->user()->account->currency }}{{ number_format($payment->amount_paid, 2) }}</td>
                        <td>{{ $payment->account->customer->name ?? ($debt->customer->name ?? 'Personal Debt') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection