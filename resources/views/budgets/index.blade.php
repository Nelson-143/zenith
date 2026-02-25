@extends('layouts.tabler')

@section('title')
    {{__('Budgets Manager') }}
@endsection

@section('me')
    @parent
@endsection

@section('budget')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{__('Budget Overview') }}</h3>
        <div class="card-actions">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createBudgetModal">
               {{ __(' Add Budget') }}
            </button>
            <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        <i class="fas fa-tags me-2"></i>{{__('Manage Categories') }}
                    </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Budget Summary -->
            <div class="col-md-6">
                <h4 class="mb-3">{{__('Summary') }}</h4>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Total Budget') }}
                        <span class="badge bg-primary rounded-pill"> {{ auth()->user()->account->currency }} {{ number_format($budgets->sum('amount')) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Expenses') }}
                        <span class="badge bg-danger rounded-pill"> {{ auth()->user()->account->currency }}{{ number_format($expenses->sum('amount')) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ __('Remaining') }}
                    <span class="badge bg-success rounded-pill"> {{ auth()->user()->account->currency }} {{ number_format($budgets->sum('amount') - $expenses->sum('amount')) }}</span>                    </li>
                </ul>
            </div>

            <!-- Progress -->
            <div class="col-md-6">
                <h4 class="mb-3">{{ __('Progress') }}</h4>
               <div class="progress mb-3">
    @php
        $percentage = $budgets->sum('amount') > 0 ? (($expenses->sum('amount') ?? 0) / $budgets->sum('amount')) * 100 : 0;
    @endphp
    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
        {{ round($percentage, 2) }}%
    </div>
</div>
                <p class="text-muted">{{ __('You have used') }} {{ round($percentage, 2) }}% {{ __('of your total budget') }}.</p>
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Budget Details Table -->
        <h4 class="mt-4">{{ __('Budget Details') }}</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Allocated') }}</th>
                        <th>{{ __('Spent') }}</th>
                        <th>{{ __('Remaining') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($budgets as $index => $budget)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $budget->category->name }}</td>
                            <td> {{ auth()->user()->account->currency }} {{ number_format($budget->amount) }}</td>
                            <td> {{ auth()->user()->account->currency }} {{ number_format($budget->spent) }}</td>
                            <td> {{ auth()->user()->account->currency }} {{ number_format($budget->amount - $budget->spent) }}</td>
                            <td>
                                <button class="btn btn-sm btn-secondary">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No budgets available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Manage Categories Modal -->
<div class="modal modal-blur fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Manage Categories') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm" action="{{ route('budget-categories.store') }}" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category-name" class="form-label">{{ __('Category Name') }}</label>
                        <input type="text" id="category-name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Budget Modal -->
<div class="modal modal-blur fade" id="createBudgetModal" tabindex="-1" aria-labelledby="createBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBudgetModalLabel">{{ __('Create Budget') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('budgets.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">{{ __('Category') }}</label>
                        <select id="budget-category" name="budget_category_id" class="form-select" required>
                            <option value="" selected disabled>{{ __('') }}</option>
                            @foreach ($budgetCategories as $budgetCategory)
                                <option value="{{ $budgetCategory->id }}">{{ $budgetCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">{{ __('Allocated Amount') }}</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="50" step="50" oninput="this.value = Math.ceil(this.value / 50) * 50" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">{{ __('Start Date') }}</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">{{ __('End Date') }}</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('growthChart').getContext('2d');
    
    // Calculate the maximum value from the growth data
    const maxValue = Math.max(...{!! json_encode($growthData['values']) !!});
    
    // Determine the step size based on the maximum value
    const stepSize = Math.ceil(maxValue / 50) * 50; // Round up to the nearest 50

    const growthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($growthData['dates']) !!},
            datasets: [{
                label: 'Budget Growth',
                data: {!! json_encode($growthData['values']) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            interaction: {
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Amount  {{ auth()->user()->account->currency }}'
                    },
                    beginAtZero: true,
                    min: 0, // Start from 0
                    ticks: {
                        stepSize: stepSize, // Set the step size dynamically
                    }
                }
            }
        }
    });
</script>

@endsection


