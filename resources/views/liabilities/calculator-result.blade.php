@extends('layouts.tabler')

@section('title', 'Loan Calculation Result')

@section('content')
<div class="container-xl">
    <div class="card shadow-sm p-4 {{ $affordable ? 'border-success' : 'border-danger' }}" style="border: 3px solid;">
        <h3 class="mb-4 text-center {{ $affordable ? 'text-success' : 'text-danger' }}">{{ __('Loan Calculation Result') }}</h3>
        <div class="card-body bg-light rounded">
            <div class="row g-3">
                <!-- Loan Amount -->
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold text-primary">{{ __('Loan Amount') }}</h5>
                    <p class="fs-5">{{ auth()->user()->account->currency }} {{ number_format($request['amount'], 2) }}</p>
                </div>
                <!-- Monthly Payment -->
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold text-primary">{{ __('Monthly Payment') }}</h5>
                    <p class="fs-5">{{ auth()->user()->account->currency }} {{ number_format($monthly_payment, 2) }}</p>
                </div>
                <!-- Total Interest -->
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold text-primary">{{ __('Total Interest') }}</h5>
                    <p class="fs-5">{{ auth()->user()->account->currency }} {{ number_format($total_interest, 2) }}</p>
                </div>
                <!-- Affordability -->
                <div class="col-md-3 text-center">
                    <h5 class="fw-bold text-primary">{{ __('Affordability') }}</h5>
                    <p class="fs-5 {{ $affordable ? 'text-success' : 'text-danger' }}">
                        {{ $affordable ? __('Affordable ✅') : __('Not Affordable ❌') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3 text-center">
            <a href="{{ route('loan.calculator') }}" class="btn btn-outline-secondary btn-lg">{{ __('Back to Calculator') }}</a>
        </div>
    </div>
</div>
@endsection
