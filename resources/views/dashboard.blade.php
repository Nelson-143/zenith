@extends('layouts.tabler')
@section('title' , 'Dashboard')


@section('content')

    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('DASHBOARD') }}
                    </div>
  <h2 class="page-title">
    |<b>{{ Auth::user()->name }}</b>,{{ $motivation }}
</h2>

<small class="text-muted">{{ __('Today is') }} ,{{ date('l, F j, Y') }}</small>


                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('pos.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                            <x-icon.plus />
                            {{ __('Create new order') }}
                        </a>
                        <a href="{{ route('pos.index') }}" class="btn btn-primary d-sm-none btn-icon"
                            aria-label="Create new report">
                            <x-icon.plus />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--- TREND DASH--->
<div class="page-body">
<div class="container-xl">
<div class="row row-deck row-cards">
<!-- Total Customers -->
@role('Super Admin')
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex align-items-center">
<div class="subheader">{{ __('Total Customers')}}</div>
<div class="ms-auto lh-1">
<div class="dropdown">
<a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Total Customers')}}</a>
<div class="dropdown-menu dropdown-menu-end">

</div>
</div>
</div>
</div>
<div class="h1 mb-3">{{ isset($customers) ? number_format($customers) : '0' }}
<span class="text-blue d-inline-flex align-items-center lh-1">
<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-users-group"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" /><path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M17 10h2a2 2 0 0 1 2 2v1" /><path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M3 13v-1a2 2 0 0 1 2 -2h2" /></svg>
</span>
</div>
<div class="d-flex mb-2">

<div class="ms-auto">

</div>
</div>
<div class="">

</div>
</div>
</div>
</div>

<!-- Total Debt -->
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex align-items-center">
<div class="subheader">{{ __('Total Debt')}}</div>
<div class="ms-auto lh-1">
<div class="dropdown">
<a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Total Debts')}}</a>
<div class="dropdown-menu dropdown-menu-end">

</div>
</div>
</div>
</div>
<div class="d-flex align-items-baseline">
<div class="h1 mb-0 me-2">
    {{ auth()->user()->account->currency }} {{ isset($debt) ? number_format($debt, 2) : '0.00' }}

<span class="text-red d-inline-flex align-items-center lh-1">
<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-moneybag-move"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.5 3h5a1.5 1.5 0 0 1 1.5 1.5a3.5 3.5 0 0 1 -3.5 3.5h-1a3.5 3.5 0 0 1 -3.5 -3.5a1.5 1.5 0 0 1 1.5 -1.5" /><path d="M12.5 21h-4.5a4 4 0 0 1 -4 -4v-1a8 8 0 0 1 14.946 -3.971" /><path d="M16 19h6" /><path d="M19 16l3 3l-3 3" /></svg>
</span>
</div>
</div>
</div>
<div id="chart-debt" class="chart-sm"></div>
</div>
</div>

<!-- Total Branches -->
<div class="col-sm-6 col-lg-3">
<div class="card">
<div class="card-body">
<div class="d-flex align-items-center">
<div class="subheader">{{ __('Total Branches')}}</div>
<div class="ms-auto lh-1">
<div class="dropdown">
<a class="text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('Your Branches')}}</a>
<div class="dropdown-menu dropdown-menu-end">

</div>
</div>
</div>
</div>

<div class="d-flex align-items-baseline">
        <div class="h1 mb-3 me-2">{{ isset($branch) ? number_format($branch) : '1' }}</div>
        <div class="me-auto">
            <span class="text-yellow d-inline-flex align-items-center lh-1">
              
            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-building-store"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4" /><path d="M5 21l0 -10.15" /><path d="M19 21l0 -10.15" /><path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" /></svg>
            </span>
        </div>
    </div>


<div id="chart-branches" class="chart-sm"></div>
</div>
</div>

</div>
<!-- Total Sales -->
<div class="col-sm-6 col-lg-3">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="subheader">{{ __('Total Sales')}}</div>
                <div class="ms-auto lh-1">
                    @if(request('period') == 'daily' || !request('period'))
                    <form method="GET" action="{{ route('dashboard') }}" id="date-select-form" class="d-flex align-items-center">
                        <input type="hidden" name="period" value="daily">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                    <path d="M16 3v4" />
                                    <path d="M8 3v4" />
                                    <path d="M4 11h16" />
                                    <path d="M11 15h1" />
                                    <path d="M12 15v3" />
                                </svg>
                            </span>
                            <input class="form-control form-control-sm" placeholder="Select date" id="selected_date" name="selected_date" value="{{ $selectedDate ?? now()->format('Y-m-d') }}" type="date" onchange="this.form.submit()">
                        </div>
                    </form>
                    @else
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <div class="ms-auto lh-1">
                            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>{{ __('Daily Sales')}}</option>
                                <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>{{ __('Weekly Sales')}}</option>
                                <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>{{ __('Monthly Sales')}}</option>
                                <option value="yearly" {{ request('period') == 'yearly' ? 'selected' : '' }}>{{ __('Yearly Sales')}}</option>
                            </select>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-baseline">
                <div class="h1 mb-0 me-2">
                    @if(isset($isFutureDate) && $isFutureDate)
                        <div class="text-warning">{{ $dailySalesMessage }}</div>
                    @elseif(isset($hasNoSales) && $hasNoSales)
                        <div class="text-muted">{{ $dailySalesMessage }}</div>
                    @else
                        {{ auth()->user()->account->currency }} {{ isset($carts) ? number_format($carts, 2) : '0.00' }}
                    @endif
                </div>
                <div class="me-auto">
                    <span class="text-green d-inline-flex align-items-center lh-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-coins">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" />
                            <path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" />
                            <path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" />
                            <path d="M3 6v10c0 .888 .772 1.45 2 2" />
                            <path d="M3 11c0 .888 .772 1.45 2 2" />
                        </svg>
                    </span>
                </div>
            </div>
            @if(request('period') == 'daily' || !request('period'))
                <div class="mt-2">
                    <small class="text-muted">
                        @if(isset($selectedDate))
                            Sales for: {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                        @else
                            Today's sales
                        @endif
                    </small>
                </div>
            @endif
        </div>
        <div id="chart-sales" class="chart-sm"></div>
    </div>
</div>

<!-- JavaScript to enhance the date picker experience -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add logic here if needed for date picker enhancements
    
    // Example: Auto-submit when clicking on the daily sales option
    const periodSelector = document.querySelector('select[name="period"]');
    if (periodSelector) {
        periodSelector.addEventListener('change', function() {
            if (this.value === 'daily') {
                // Show date picker when daily is selected
                setTimeout(() => {
                    document.getElementById('selected_date').click();
                }, 100);
            }
        });
    }
});
</script>
<script>
    function updateSalesChart(period) {
        // Make an AJAX request to fetch data based on the selected period
        fetch(`/sales-data?period=${period}`)
            .then(response => response.json())
            .then(data => {
                // Update the chart with the new data
                console.log('Sales data for ' + period + ':', data);
                // Here you would update your chart with the new data
            })
            .catch(error => console.error('Error fetching sales data:', error));
    }
</script>
@endrole
  
<!--- STATIC DASH ---->
                <div class="col-12">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-packages" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                                                    <path d="M2 13.5v5.5l5 3" />
                                                    <path d="M7 16.545l5 -3.03" />
                                                    <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                                                    <path d="M12 19l5 3" />
                                                    <path d="M17 16.5l5 -3" />
                                                    <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" />
                                                    <path d="M7 5.03v5.455" />
                                                    <path d="M12 8l5 -3" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $products }} {{ __('Products')}}
                                            </div>
                                            <div class="text-muted">
                                                {{ $categories }} {{ __('categories')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M17 17h-11v-14h-2" />
                                                    <path d="M6 5l14 1l-1 7h-13" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $orders }} {{ __('Orders')}}
                                            </div>
                                            <div class="text-muted">
                                                {{ $todayOrders }} {{ __('shipped')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-twitter text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-truck-delivery" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M5 17h-2v-4m-1 -8h11v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                                                    <path d="M3 9l4 0" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $purchases }} {{ __('Purchases')}}
                                            </div>
                                            <div class="text-muted">
                                                {{ $todayPurchases }} {{ __('today')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="bg-facebook text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-files" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                                    <path
                                                        d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                                    <path
                                                        d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $quotations }} {{ __('Quotations')}}
                                            </div>
                                            <div class="text-muted">
                                                {{ $todayQuotations }} {{ __('today')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</div>
                <div class="row">
                @role('Super Admin')
   <!-- Line Chart: Business Growth Rate -->
<div class="col-md-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Daily Business Growth Rate') }}</h3>
        </div>
        <div class="card-body">
            <canvas id="growthLineChart" width="400" height="400"></canvas>
        </div>
    </div>
</div>

    <!-- Pie Chart: Out of Stock Products -->

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('Out of Stock Products') }}
            </div>
            <div class="card-body">
                <canvas id="supplierPieChart" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
</div>
@endrole

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Data for the daily growth line chart
  const growthData = {
    labels: {!! json_encode($days) !!}, // Last 14 days (e.g., ["May 01", "May 02", ...])
    datasets: [
      {
        label: 'Daily Sales ({{ auth()->user()->account->currency }})',
        data: {!! json_encode($salesValues) !!}, // Daily sales values
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        yAxisID: 'y',
        fill: false,
        tension: 0.1
      },
      {
        label: 'Growth Rate (%)',
        data: {!! json_encode($dailyGrowthRates) !!}, // Daily growth rates
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        yAxisID: 'y1',
        fill: false,
        borderDash: [5, 5],
        tension: 0.1
      }
    ]
  };

  // Create the growth line chart
  const ctx1 = document.getElementById('growthLineChart').getContext('2d');
  new Chart(ctx1, {
    type: 'line',
    data: growthData,
    options: {
      responsive: true,
      interaction: {
        mode: 'index',
        intersect: false,
      },
      stacked: false,
      plugins: {
        title: {
          display: true,
          text: 'Daily Sales and Growth Rate (Last 14 Days)'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let label = context.dataset.label || '';
              if (label) {
                label += ': ';
              }
              if (context.datasetIndex === 0) {
                label += new Intl.NumberFormat('en-US', { 
                  style: 'currency',
                  currency: '{{ auth()->user()->account->currency }}' 
                }).format(context.parsed.y);
              } else {
                label += context.parsed.y + '%';
              }
              return label;
            }
          }
        }
      },
      scales: {
        y: {
          type: 'linear',
          display: true,
          position: 'left',
          title: {
            display: true,
            text: 'Sales Amount ({{ auth()->user()->account->currency }})'
          },
          beginAtZero: true
        },
        y1: {
          type: 'linear',
          display: true,
          position: 'right',
          title: {
            display: true,
            text: 'Growth Rate (%)'
          },
          // Grid line settings
          grid: {
            drawOnChartArea: false, // Only draw grid lines for the primary y-axis
          },
          min: -100,
          max: 100,
          ticks: {
            callback: function(value) {
              return value + '%';
            }
          }
        }
      }
    }
  });

  // Data for the pie chart
  const pieData = {
    labels: {!! json_encode($pieChartData['labels']) !!},
    datasets: [{
      data: {!! json_encode($pieChartData['data']) !!},
      backgroundColor: ['#36A2EB', '#FF6384'],
    }]
  };

  // Create the pie chart
  const ctx2 = document.getElementById('supplierPieChart').getContext('2d');
  new Chart(ctx2, {
    type: 'pie',
    data: pieData,
    options: {
      responsive: true,
    }
  });
</script>

            </div>
        </div>
    </div>
@endsection

@push('page-libraries')
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
@endpush

@pushonce('page-scripts')
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-revenue-bg'), {
                chart: {
                    type: "area",
                    fontFamily: 'inherit',
                    height: 40.0,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    opacity: .16,
                    type: 'solid'
                },
                stroke: {
                    width: 2,
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "Profits",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93,
                        53, 61, 27, 54, 43, 19, 46, 39, 62, 51, 35, 41, 67
                    ]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24',
                    '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29',
                    '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04',
                    '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09',
                    '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14',
                    '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [tabler.getColor("primary")],
                legend: {
                    show: false,
                },
            })).render();
        });
        // @formatter:on
    </script>
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-new-clients'), {
                chart: {
                    type: "line",
                    fontFamily: 'inherit',
                    height: 40.0,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                fill: {
                    opacity: 1,
                },
                stroke: {
                    width: [2, 1],
                    dashArray: [0, 3],
                    lineCap: "round",
                    curve: "smooth",
                },
                series: [{
                    name: "May",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93,
                        53, 61, 27, 54, 43, 4, 46, 39, 62, 51, 35, 41, 67
                    ]
                }, {
                    name: "April",
                    data: [93, 54, 51, 24, 35, 35, 31, 67, 19, 43, 28, 36, 62, 61, 27, 39, 35,
                        41, 27, 35, 51, 46, 62, 37, 44, 53, 41, 65, 39, 37
                    ]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24',
                    '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29',
                    '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04',
                    '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09',
                    '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14',
                    '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [tabler.getColor("primary"), tabler.getColor("gray-600")],
                legend: {
                    show: false,
                },
            })).render();
        });
        // @formatter:on
    </script>
    <script>
        // @formatter:off
        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('chart-active-users'), {
                chart: {
                    type: "bar",
                    fontFamily: 'inherit',
                    height: 40.0,
                    sparkline: {
                        enabled: true
                    },
                    animations: {
                        enabled: false
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: '50%',
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                fill: {
                    opacity: 1,
                },
                series: [{
                    name: "Profits",
                    data: [37, 35, 44, 28, 36, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93,
                        53, 61, 27, 54, 43, 19, 46, 39, 62, 51, 35, 41, 67
                    ]
                }],
                tooltip: {
                    theme: 'dark'
                },
                grid: {
                    strokeDashArray: 4,
                },
                xaxis: {
                    labels: {
                        padding: 0,
                    },
                    tooltip: {
                        enabled: false
                    },
                    axisBorder: {
                        show: false,
                    },
                    type: 'datetime',
                },
                yaxis: {
                    labels: {
                        padding: 4
                    },
                },
                labels: [
                    '2020-06-20', '2020-06-21', '2020-06-22', '2020-06-23', '2020-06-24',
                    '2020-06-25', '2020-06-26', '2020-06-27', '2020-06-28', '2020-06-29',
                    '2020-06-30', '2020-07-01', '2020-07-02', '2020-07-03', '2020-07-04',
                    '2020-07-05', '2020-07-06', '2020-07-07', '2020-07-08', '2020-07-09',
                    '2020-07-10', '2020-07-11', '2020-07-12', '2020-07-13', '2020-07-14',
                    '2020-07-15', '2020-07-16', '2020-07-17', '2020-07-18', '2020-07-19'
                ],
                colors: [tabler.getColor("primary")],
                legend: {
                    show: false,
                },
            })).render();
        });
        // @formatter:on
    </script>
@endpushonce
