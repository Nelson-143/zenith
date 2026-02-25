@extends('layouts.tabler')

@section('title', 'Expenses')

@section('me')
    @parent
@endsection

@section('matumizi')
<div class="container-xl">
    <!-- Page Header -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">{{ __('Expense Management') }}</h2>
                <div class="text-muted mt-1">{{ __('Track and manage your business expenses') }}</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="d-flex">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                        <i class="fas fa-plus me-2"></i>{{ __('Add Expense') }}
                    </button>
                    <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        <i class="fas fa-tags me-2"></i>{{ __('Manage Categories') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filter-category" class="form-label">{{ __('Category') }}</label>
            <select id="expense-category" name="expense_category_id" class="form-select" required>
                <option value="" selected disabled>{{ __('Select Expense Category') }}</option>
                @foreach ($expenseCategories as $expenseCategory)
                    <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                @endforeach
            </select>

        </div>
        <div class="col-md-4">
            <label for="filter-date-from" class="form-label">{{ __('From Date') }}</label>
            <input type="date" id="filter-date-from" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="filter-date-to" class="form-label">{{ __('To Date') }}</label>
            <input type="date" id="filter-date-to" class="form-control">
        </div>
        <div class="col-md-12 mt-2">
            <button class="btn btn-secondary w-100" onclick="filterExpenses()">{{ __('Apply Filters') }}</button>
        </div>
    </div>

    <!-- Expense Trends Chart -->
    <div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">{{ __('Expense Trends') }}</h3>
    </div>
    <div class="card-body">
        <canvas id="expenseTrendsChart"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Expense Table -->
     @role('Super Admin')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Expense Records') }}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table" id="expensesTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Attachment') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $expense->category->name }}</td>
                            <td>{{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->description }}</td>
                        <td>{{ $expense->expense_date }}
                        </td>
                        <td>
                                @if ($expense->attachment)
                                    <a href="{{ asset('storage/'.$expense->attachment) }}" class="btn btn-sm btn-link" target="_blank">View{{ __('') }}</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="deleteExpense('{{ $expense->id }}')">Delete{{ __('') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <p>No expenses found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $expenses->links() }}
        </div>
    </div>
    @endrole
</div>

<!-- Manage Categories Modal -->
<div class="modal modal-blur fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Manage Categories') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryForm" action="{{ route('expense-categories.store') }}" method="POST">
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
<!-- Add Expense Modal -->
<div class="modal modal-blur fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Expense') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="expenseForm" action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="expense-category" class="form-label">{{ __('Category') }}</label>
                        <select id="expense-category" name="expense_category_id" class="form-select" required>
                        <option value="" selected disabled>{{ __('Select Expense Category') }}</option>
                        @foreach ($expenseCategories as $expenseCategory)
                            <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                        @endforeach
                    </select>

                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="expense-amount" class="form-label">{{ __('Amount') }}</label>
                        <input type="number" step="0.01" id="expense-amount" name="amount" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="expense-description" class="form-label">{{ __('Description') }}</label>
                        <textarea id="expense-description" name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <!-- Date -->
                    <div class="mb-3">
                        <label for="expense-date" class="form-label">{{ __('Expense Date') }}</label>
                        <input type="date" id="expense-date" name="expense_date" class="form-control" required>
                    </div>

                    <!-- Attachment (Optional) -->
                    <div class="mb-3">
                        <label for="expense-attachment" class="form-label">{{ __('Attachment (Optional)') }}</label>
                        <input type="file" id="expense-attachment" name="attachment" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Save Expense') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function filterExpenses() {
        const category = document.getElementById('filter-category').value;
        const dateFrom = document.getElementById('filter-date-from').value;
        const dateTo = document.getElementById('filter-date-to').value;

        let url = `/expenses?category=${category}&date_from=${dateFrom}&date_to=${dateTo}`;
        window.location.href = url;
    }

    function deleteExpense(id) {
        if (confirm('Are you sure you want to delete this expense?')) {
            fetch(`/expenses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => {
                if (response.ok) {
                    alert('Expense deleted successfully!');
                    location.reload();
                } else {
                    alert('Failed to delete the expense.');
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('expenseTrendsChart').getContext('2d');
        const expenseTrendsData = @json($expenseTrendsData);

        // Validate and prepare data
        if (!Array.isArray(expenseTrendsData)) {
            console.error('Invalid data format:', expenseTrendsData);
            expenseTrendsData = [];
        }

        const labels = expenseTrendsData.length ? expenseTrendsData.map(item => item?.date || 'Unknown') : ['No Data'];
        const data = expenseTrendsData.length ? expenseTrendsData.map(item => item?.total || 0) : [0];

        // Debugging: Log data to the console
        console.log('Raw Data:', expenseTrendsData);
        console.log('Labels:', labels);
        console.log('Data:', data);

        // Initialize the chart
        const expenseTrendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expenses',
                    data: data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0,
                        suggestedMax: Math.max(...data, 100) // Ensure visibility even with low data
                    }
                },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Expense Trends Over Time' },
                },
            },
        });
    });
</script>
@endsection
