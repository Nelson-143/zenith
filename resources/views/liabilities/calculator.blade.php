@extends('layouts.tabler')

@section('title', 'Loan Calculator')

@section('content')
<div class="container-xl">
    <div class="card shadow-sm p-4">
        <h3 class="mb-4 text-center">{{ __('Loan Calculator') }}</h3>
        <h5 class="text-muted text-center">{{ __('Check if the loan is affordable for your business') }}</h5>
        <form action="{{ route('calculate.loan') }}" method="POST" class="mt-4">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('Loan Amount') }}</label>
                    <input type="number" name="amount" class="form-control" placeholder="Enter loan amount" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('Interest Rate') }} (%)</label>
                    <input type="number" name="interest_rate" class="form-control" placeholder="e.g., 5" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">{{ __('Term (Years)') }}</label>
                    <input type="number" name="term" class="form-control" placeholder="e.g., 3" required>
                </div>
            </div>
            <div class="mt-4 text-center">
                <button type="submit" class="btn btn-success btn-lg w-50">{{ __('Calculate') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
