@extends('layouts.tabler')

@section('title')
Payments History
@endsection

@section('content')
<div class="container">
    <h1>{{ __('Payment History for') }} {{ $liability->name }}</h1> <!-- Updated to show liability name -->

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
                        <td>{{ auth()->user()->account->currency }}{{ number_format($payment->amount_paid, 2) }}</td>
                        <td>{{ $payment->account->customer->name ?? 'Personal Debt' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="mt-4">{{ __('Payment Scheme') }}</h2>
    <div class="card">
        <div class="card-body">
            <h5>{{ __('Total Liability Amount') }}: {{ auth()->user()->account->currency }}{{ number_format($liability->amount, 2) }}</h5>
            <h5>{{ __('Total Amount Paid') }}: {{ auth()->user()->account->currency }}{{ number_format($payments->sum('amount_paid'), 2) }}</h5>
            <h5>{{ __('Remaining Balance') }}: {{ auth()->user()->account->currency }}{{ number_format($liability->amount - $payments->sum('amount_paid'), 2) }}</h5>

            @php
                $remainingBalance = $liability->amount - $payments->sum('amount_paid');
                $monthlyPayment = 100; // Example fixed monthly payment
                $monthsToPayOff = ceil($remainingBalance / $monthlyPayment);
                $totalPayment = $monthlyPayment * $monthsToPayOff;
            @endphp

            <h5>{{ __('Suggested Monthly Payment') }}: {{ auth()->user()->account->currency }}{{ number_format($monthlyPayment, 2) }}</h5>
            <h5>{{ __('Estimated Months to Pay Off') }}: {{ $monthsToPayOff }}</h5>
            <h5>{{ __('Total Payment with Suggested Plan') }}: {{ auth()->user()->account->currency }}{{ number_format($totalPayment, 2) }}</h5>

            <p>{{ __('This payment scheme is based on a fixed monthly payment of ') }} {{ auth()->user()->account->currency }}{{ number_format($monthlyPayment, 2) }}. {{ __('Adjust the payment amount according to your financial situation for a more personalized plan') }}.</p>
        </div>
    </div>
</div>
@endsection