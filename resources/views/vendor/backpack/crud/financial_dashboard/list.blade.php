{{-- resources/views/vendor/backpack/crud/financial_dashboard/list.blade.php --}}
@extends(backpack_view('blank'))

@section('content')
<div class="row mb-4">
    <!-- Revenue Card -->
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-muted">Total Revenue</h6>
                <h2>${{ number_format($financialMetrics['total_revenue'], 2) }}</h2>
            </div>
        </div>
    </div>
    
    <!-- Active Subs Card -->
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-muted">Active Subs</h6>
                <h2>{{ $financialMetrics['active_subscriptions'] }}</h2>
            </div>
        </div>
    </div>
    
    <!-- MRR Card -->
    <div class="col-md-3">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-muted">Monthly Revenue</h6>
                <h2>${{ number_format($financialMetrics['mrr'], 2) }}</h2>
            </div>
        </div>
    </div>
    
    <!-- ARPU Card -->
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="card-title text-uppercase text-muted">Avg Revenue/User</h6>
                <h2>${{ number_format($financialMetrics['arpu'], 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Payment List -->
<div class="row">
    <div class="col-md-12">
        @include('crud::inc.datatable')
    </div>
</div>
@endsection