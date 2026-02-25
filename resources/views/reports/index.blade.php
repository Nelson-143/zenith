@extends('layouts.tabler')

@section('title', 'Business Reports 🚀')

@section('me')
    @parent
@endsection

@section('report')
<div class="container-xl">
    <!-- Business Overview Section -->
    <div class="row row-cards mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-light">
                    <h2>{{ __('Business Overview') }}</h2>
                    <p class="text-muted">{{ __('A glance summary of your business') }}🚀.</p>
                </div>
                <div class="card-body d-flex justify-content-around align-items-center">
                    <div class="text-center">
                        <h5>{{ __('Total Sales') }}</h5>
                        <p class="h3 text-success">{{ isset($carts) ? number_format($carts, 2) : 'Tsh 0.00' }}</p>
                    </div>
                    <div class="text-center">
                        <h5>{{ __('Total Expenses') }}</h5>
                        <p class="h3 text-danger">{{ isset($totalExpenses) ? number_format($totalExpenses, 2) : 'Tsh 0.00' }}</p>
                    </div>
                    <div class="text-center">
                        <h5>{{ __('Profit') }}</h5>
                        <p class="h3 text-primary">{{ isset($profit) ? number_format($profit, 2) : 'Tsh 0.00' }}</p>
                    </div>

                  
                </div>
                <!-- display -->
                <div class="card-body d-flex justify-content-around align-items-center">
                <div class="text-center">
                    <h5>{{ __('Total Available Stock') }}</h5>
                    <p class="h3 text-success">{{ isset($totalAvailableStock) ? number_format($totalAvailableStock, 2) : '0' }}</p>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-packages"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" /><path d="M2 13.5v5.5l5 3" /><path d="M7 16.545l5 -3.03" /><path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" /><path d="M12 19l5 3" /><path d="M17 16.5l5 -3" /><path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" /><path d="M7 5.03v5.455" /><path d="M12 8l5 -3" /></svg>

                </div>
                <div class="text-center">
                    <h5>{{ __('Low Stock Items') }}</h5>
                    <p class="h3 text-warning">{{ isset($lowStockItems) ? number_format($lowStockItems, 2) : '0' }}</p>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag-exclamation"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 21h-6.426a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.258 1.678" /><path d="M9 11v-5a3 3 0 0 1 6 0v5" /><path d="M19 16v3" /><path d="M19 22v.01" /></svg>
                </div>
                <div class="text-center">
                    <h5>{{ __('Out of Stock Items') }}</h5>
                    <p class="h3 text-danger">{{ isset($outOfStockItems) ? number_format($outOfStockItems, 2) : '0' }}</p>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 21h-4.426a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304h11.339a2 2 0 0 1 1.977 2.304l-.506 3.287" /><path d="M9 11v-5a3 3 0 0 1 6 0v5" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg>
                </div>
                <div class="text-center">
                    <h5>{{ __('Total Stock Value') }}</h5>
                    <p class="h3 text-primary">{{ isset($totalStockValue) ? number_format($totalStockValue, 2) : 'Tsh 0.00' }}</p>
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-businessplan"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M16 6m-5 0a5 3 0 1 0 10 0a5 3 0 1 0 -10 0" /><path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" /><path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" /><path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4" /><path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M5 15v1m0 -8v1" /></svg>
                </div>
            </div>
            </div>
        </div>
        
    </div>

    <!-- Date Range Selector -->
    <!-- <div class="row mb-4">
        <div class="col-md-12">
            <label for="dateRange" class="form-label">Select Date Range:</label>
            <input type="text" id="dateRange" class="form-control" placeholder="Select date range">
        </div>
    </div> -->

    <!-- Visual Trends Section -->
    <div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h3 class="card-title">{{ __('Trends and Insights') }}</h3>
                <div class="card-actions">
                    <form id="report-type-form">
                        <select class="form-select form-select-sm" id="report-type-select" name="report_type">
                            <option value="daily" {{ $reportType === 'daily' ? 'selected' : '' }}>{{ __('Daily') }}</option>
                            <option value="weekly" {{ $reportType === 'weekly' ? 'selected' : '' }}>{{ __('Weekly') }}</option>
                            <option value="monthly" {{ $reportType === 'monthly' ? 'selected' : '' }}>{{ __('Monthly') }}</option>
                            <option value="yearly" {{ $reportType === 'yearly' ? 'selected' : '' }}>{{ __('Yearly') }}</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="card-body text-center">
                <canvas id="reportChart" height="100"></canvas>
                @if (!isset($chartData) || empty($chartData))
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-chart-pie-2 text-muted mx-auto">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                        <path d="M22 11.73V9a4 4 0 0 0-4-4H8.82a4 4 0 0 0-4 4v11a4 4 0 0 0 4 4H14a4 4 0 0 0 4-4V7.39a2 2 0 0 1 2-2"></path>
                        <path d="M16 17v4a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-4"></path>
                        <path d="M14 3a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v4"></path>
                    </svg>
                    <p class="text-muted mt-3">No data available yet. Start generating reports to see trends here.</p>
                @endif
            </div>
        </div>
    </div>
</div>

    <!-- Key Performance Indicators Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h3 class="card-title">{{ __('Key Performance Indicators ') }}(KPIs)</h3>
                </div>
                <div class="card-body d-flex justify-content-around">
                    <div class="text-center">
                        <h5>{{ __('Gross Margin') }}</h5>
                        <p class="h3 text-success">{{ isset($grossMargin) ? number_format($grossMargin, 2) . '%' : '0%' }}</p>
                    </div>
                    <div class="text-center">
                        <h5>{{ __('Expense Ratio') }}</h5>
                        <p class="h3 text-danger">{{ isset($expenseRatio) ? number_format($expenseRatio, 2) . '%' : '0%' }}</p>
                    </div>
                    <div class="text-center">
                        <h5>{{ __('Year-to-Date Performance') }}</h5>
                        <p class="h3 text-primary">{{ isset($ytdPerformance) ? number_format($ytdPerformance, 2) : 'Tsh 0.00' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Accounting Reports Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title">{{ __('Accounting Reports') }}</h3>
            </div>
            <div class="card-body">
                <div class="accordion" id="accounting-reports">
                    <!-- Income Statement -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#income-statement">
                                {{ __('Income Statement') }}
                            </button>
                        </h2>
                        <div id="income-statement" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Description') }}</th>
                                            <th> {{ __('Amount') }}( {{ auth()->user()->account->currency }})</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span data-bs-toggle="tooltip" title="Total income from sales.">{{ __('Revenue') }}</span></td>
                                            <td>{{ number_format($incomeStatement['revenue'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><span data-bs-toggle="tooltip" title="Cost of goods sold.">{{ __('COGS') }}</span></td>
                                            <td>{{ number_format($incomeStatement['cogs'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><span data-bs-toggle="tooltip" title="Revenue minus COGS.">{{ __('Gross Profit') }}</span></td>
                                            <td>{{ number_format($incomeStatement['grossProfit'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><span data-bs-toggle="tooltip" title="Operating expenses like salaries, rent, etc.">{{ __('Operating Expenses') }}</span></td>
                                            <td>{{ number_format($incomeStatement['expenses'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><span data-bs-toggle="tooltip" title="Gross profit minus operating expenses.">{{ __('Net Income') }}</span></td>
                                            <td>{{ number_format($incomeStatement['netIncome'], 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                      <!-- Cash Flow -->
                      <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cash-flow">
                                {{ __('Cash Flow') }}
                            </button>
                        </h2>
                        <div id="cash-flow" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Category') }}</th>
                                            <th>{{ __('Amount') }} ( {{ auth()->user()->account->currency }})</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ __('Inflows') }}</strong></td>
                                            <td></td>
                                        </tr>
                                        @foreach($cashFlow['inflows'] as $inflow => $amount)
                                            <tr>
                                                <td>{{ ucfirst($inflow) }}</td>
                                                <td>{{ number_format($amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>{{ __('Total Inflows') }}</strong></td>
                                            <td>{{ number_format(array_sum($cashFlow['inflows']), 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>{{ __('Outflows') }}</strong></td>
                                            <td></td>
                                        </tr>
                                        @foreach($cashFlow['outflows'] as $outflow => $amount)
                                            <tr>
                                                <td>{{ ucfirst($outflow) }}</td>
                                                <td>{{ number_format($amount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><strong>{{ __('Total Outflows') }}</strong></td>
                                            <td>{{ number_format(array_sum($cashFlow['outflows']), 2) }}</td>
                                        </tr>

                                        <tr>
                                            <td><strong>{{ __('Net Cash Flow') }}</strong></td>
                                            <td>{{ number_format(array_sum($cashFlow['inflows']) - array_sum($cashFlow['outflows']), 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Sheet -->


                  
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Recommendations Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title">{{ __('AI-Driven Recommendations') }}</h3>
            </div>
            <div class="card-body">
                @forelse($recommendations as $recommendation)
                    <div class="alert alert-info d-flex justify-content-between align-items-center">
                        <span>{{ $recommendation->recommendation }}</span>
                       
                    </div>
                @empty
                    <div class="alert alert-secondary text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-lightbulb text-muted mx-auto">
                            <path d="M12 3v4"></path>
                            <path d="M12 13v8"></path>
                            <path d="M12 7.5a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"></path>
                        </svg>
                        <p class="mt-2">No recommendations available at this time. Generate more data for insights!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Actionable Insights Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h3 class="card-title">{{ __('Actionable Insights') }}</h3>
            </div>
            <div class="card-body">
                @forelse($actionableInsights as $insight)
                <div class="alert alert-{{ $insight['status'] }}">
    <div class="d-flex justify-content-between align-items-center">
        <span><strong>{{ $insight['message'] }}</strong></span>
    </div>
    <div class="mt-2">
        <p class="mb-0">{{ $insight['details'] }}</p>
    </div>
</div>
        
                @empty
                    <div class="alert alert-secondary text-center">
                        <p>No actionable insights available at this time.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
   

   

    <!-- Detailed Reports Section -->
    

<!-- Date Search Form and Reports Table (Existing Code) -->


<!-- Chart.js Initialization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('reportChart').getContext('2d');
        const reportChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels ?? []),
                datasets: [{
                    label: 'Sales',
                    data: @json($chartData ?? []),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Handle report type change
        const reportTypeSelect = document.getElementById('report-type-select');
        reportTypeSelect.addEventListener('change', function () {
            const form = document.getElementById('report-type-form');
            form.submit();
        });
    });

    
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('yearlyComparisonChart').getContext('2d');
        const yearlyComparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($yearlyLabels ?? []),
                datasets: [{
                    label: 'Sales',
                    data: @json($yearlySales ?? []),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    function addBalanceSheetItem() {
        const assetName = document.getElementById('assetName').value;
        const assetAmount = parseFloat(document.getElementById('assetAmount').value);
        const liabilityName = document.getElementById('liabilityName').value;
        const liabilityAmount = parseFloat(document.getElementById('liabilityAmount').value);

        // Add logic to save the data (e.g., send to server via AJAX)
        console.log('Asset:', assetName, assetAmount);
        console.log('Liability:', liabilityName, liabilityAmount);

        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('balanceSheetModal'));
        modal.hide();
    }

    function addBalanceSheetItem() {
        const assetName = document.getElementById('assetName').value;
        const assetAmount = parseFloat(document.getElementById('assetAmount').value);
        const liabilityName = document.getElementById('liabilityName').value;
        const liabilityAmount = parseFloat(document.getElementById('liabilityAmount').value);

        // Add logic to save the data (e.g., send to server via AJAX)
        console.log('Asset:', assetName, assetAmount);
        console.log('Liability:', liabilityName, liabilityAmount);

        // Close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('balanceSheetModal'));
        modal.hide();
    }

    let assetIndex = {{ isset($balanceSheet['assets']) ? count($balanceSheet['assets']) : 1 }};
    let liabilityIndex = {{ isset($balanceSheet['liabilities']) ? count($balanceSheet['liabilities']) : 1 }};

    function addAssetField() {
        const container = document.getElementById('assets-container');
        const newField = `
            <div class="asset-item mb-2">
                <input type="text" class="form-control mb-2" name="assets[${assetIndex}][name]" placeholder="Asset Name" required>
                <input type="number" class="form-control" name="assets[${assetIndex}][amount]" placeholder="Amount (Tsh)" required>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newField);
        assetIndex++;
    }

    function addLiabilityField() {
        const container = document.getElementById('liabilities-container');
        const newField = `
            <div class="liability-item mb-2">
                <input type="text" class="form-control mb-2" name="liabilities[${liabilityIndex}][name]" placeholder="Liability Name" required>
                <input type="number" class="form-control" name="liabilities[${liabilityIndex}][amount]" placeholder="Amount (Tsh)" required>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newField);
        liabilityIndex++;
    }
</script>
@endsection
