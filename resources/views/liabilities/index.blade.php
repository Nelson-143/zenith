@extends('layouts.tabler')

@section('title', 'Liability Management')

@section('content')
<div class="container mt-4">
    <div class="row row-deck row-cards">
        <!-- Financial Metrics Cards -->
        <div class="col-12">
            <div class="row row-cards">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ __('Total Liabilities') }}</div>
                            </div>
                            <div class="h1 mb-3">{{ auth()->user()->account->currency }} {{ number_format($metrics['total_liabilities'], 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ __('Debt-to-Income Ratio') }}</div>
                            </div>
                            <div class="h1 mb-3">{{ number_format($metrics['debt_to_income'], 1) }}%</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ __('Monthly Cash Flow') }}</div>
                            </div>
                            <div class="h1 mb-3">{{ auth()->user()->account->currency }} {{ number_format($metrics['cash_flow'], 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">{{ __('Risk Level') }}</div>
                            </div>
                            <div class="h1 mb-3">
                                <span class="badge bg-{{ $riskAnalysis['risk_level'] == 'High' ? 'danger' : ($riskAnalysis['risk_level'] == 'Moderate' ? 'warning' : 'success') }}">
                                    {{ $riskAnalysis['risk_level'] }} {{ __('Risk') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
/* General card styling for consistency and readability */
.card {
    border-radius: 0.5rem; /* Rounded corners */
    font-size: 0.9rem; /* General font size */
    margin-bottom: 1rem; /* Space between cards */
}

.card-title {
    font-size: 1rem; /* Adjusted title size for better balance */
    font-weight: 600; /* Slightly bold for emphasis */
}

.card-text {
    font-size: 1.5rem; /* Main text size for prominence */
    font-weight: 700; /* Bold to make the data stand out */
    margin-bottom: 0.5rem; /* Space below main text */
}

.text-muted {
    font-size: 0.85rem; /* Slightly smaller muted text */
}
</style>

        <!-- Liabilities Table -->
        <div class="container mt-4">
    <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Liabilities') }}</h3>
                    <div class="card-actions">
                        <a href="{{ route('loan.calculator') }}" class="btn btn-primary">
                            {{ __('Loan Calculator') }}
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-liability">
                            {{ __('Add Liability') }}
                        </button>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#consolidate">
                        {{ __('Consolidate Debts') }}
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Remaining') }}</th>
                                <th>{{ __('Interest Rate') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Priority') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($liabilities as $liability)
                            <tr>
                                <td>{{ $liability->name }}</td>
                                <td>{{ ucfirst($liability->type) }}</td>
                                <td>{{ auth()->user()->account->currency }} {{ number_format($liability->amount, 2) }}</td>
                                <td>{{ auth()->user()->account->currency }} {{ number_format($liability->remaining_balance, 2) }}</td>
                                <td>{{ $liability->interest_rate }}%</td>
                                <td>{{ date('M d, Y', strtotime($liability->due_date)) }}</td>
                                <td>
                                    <span class="badge bg-{{ $liability->priority == 'high' ? 'danger' : ($liability->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($liability->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $liability->remaining_balance <= 0 ? 'success' : 'warning' }}">
                                        {{ $liability->remaining_balance <= 0 ? 'Paid' : 'Active' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                        data-bs-target="#payment-modal" 
                                        data-liability-id="{{ $liability->id }}">
                                        {{ __('Pay') }}
                                    </button>

                                    <a href="{{ route('liabilities.history', $liability) }}" 
                                        class="btn btn-sm btn-info">
                                        {{ __('History') }}
                                    </a>
                                    <form action="{{ route('liabilities.destroy', $liability->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this liability?');">{{ __('Delete') }}</button>
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Liability Modal -->
<div class="modal modal-blur fade" id="add-liability" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('liabilities.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add New Liability') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Amount') }}</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Interest Rate') }} (%)</label>
                        <input type="number" step="0.01" class="form-control" name="interest_rate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Due Date') }}</label>
                        <input type="date" class="form-control" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Priority') }}y</label>
                        <select class="form-select" name="priority">
                            <option value="high">{{ __('High') }}</option>
                            <option value="medium" selected>{{ __('Medium') }}</option>
                            <option value="low">{{ __('Low') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Type') }}</label>
                        <select class="form-select" name="type">
                            <option value="formal">{{ __('Formal Loan') }}</option>
                            <option value="informal">{{ __('Informal Debt') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Add Liability') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal modal-blur fade" id="payment-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="payment-form" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Make Payment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="liability_id" id="liability-id">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Amount') }}</label>
                        <input type="number" step="0.01" class="form-control" name="amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Record Payment') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Consolidate Modal -->
<div class="modal modal-blur fade" id="consolidate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('liabilities.consolidate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Consolidate Debts') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Consolidation Amount') }}</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Interest Rate') }} (%)</label>
                        <input type="number" name="interest_rate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Term (Years)') }}</label>
                        <input type="number" name="term" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ __('Consolidate Debts') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment modal
    $('#payment-modal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const liabilityId = button.data('liability-id');
        const form = document.getElementById('payment-form');
        form.action = `/liabilities/${liabilityId}/pay`;
        document.getElementById('liability-id').value = liabilityId;
    });
});

const ctx = document.getElementById('financialHealthChart').getContext('2d');
const financialHealthChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Total Liabilities', 'Total Paid', 'Cash Flow'],
        datasets: [{
            label: 'Financial Health',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

@endsection