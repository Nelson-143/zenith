@extends('layouts.tabler')

@section('title', 'Edit Debt')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Debt</div>
                <div class="card-body">
                    <form action="{{ route('debts.update', $debt->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control">
                                <option value="">Personal Debt</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $debt->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" value="{{ $debt->amount }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $debt->due_date }}" required>
                        <button type="submit" class="btn btn-primary">Update Debt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection