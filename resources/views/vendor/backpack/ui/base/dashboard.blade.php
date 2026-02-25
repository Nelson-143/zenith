@extends(backpack_view('blank'))

@section('content')
<div class="container-fluid">
    <!-- System Health Row -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <i class="la la-heartbeat"></i> System Health
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($system as $metric => $value)
                        <div class="col-md-2 text-center">
                            <div class="metric-card">
                                <h5 class="text-uppercase text-muted small">{{ str_replace('_', ' ', $metric) }}</h5>
                                <h2 class="mb-0">{{ $value }}</h2>
                            </div>
                        </div>
                        @endforeach
                        <div class="col-md-2">
                            <a href="{{ route('admin.clear.cache') }}" class="btn btn-sm btn-warning">
                                <i class="la la-broom"></i> Clear Cache
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Analytics Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="la la-users"></i> User Analytics
                </div>
                <div class="card-body">
                    <canvas id="userChart" height="150"></canvas>
                    <hr>
                    <div class="row text-center">
                        @foreach($users as $metric => $value)
                        <div class="col-3">
                            <h5>{{ $value }}</h5>
                            <small class="text-muted">{{ str_replace('_', ' ', $metric) }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Alerts -->
        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <i class="la la-shield-alt"></i> Security Alerts
                </div>
                <div class="card-body">
                    @if(count($security['suspicious_ips']) > 0)
                        <div class="alert alert-danger">
                            <h5>Suspicious Activity Detected!</h5>
                            <ul class="list-unstyled">
                                @foreach($security['suspicious_ips'] as $ip)
                                <li>
                                    {{ $ip->ip_address }} ({{ $ip->attempts }} attempts)
                                    <a href="{{ route('admin.ban.ip', $ip->ip_address) }}" class="btn btn-xs btn-danger float-right">
                                        Ban IP
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        @foreach($security as $metric => $value)
                            @if(!is_array($value))
                            <div class="col-4 text-center">
                                <h5>{{ $value }}</h5>
                                <small class="text-muted">{{ str_replace('_', ' ', $metric) }}</small>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <i class="la la-history"></i> Recent Activity
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>IP</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Block</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(DB::table('activity_log')->latest()->take(10)->get() as $log)
                            <tr class="{{ $log->event == 'failed_login' ? 'table-danger' : '' }}">
                                <td>{{ $log->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($log->user_id)
                                        {{ User::find($log->user_id)->name ?? 'Deleted' }}
                                    @else
                                        Guest
                                    @endif
                                </td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $log->event }}</td>
                                <td>{{ Str::limit($log->description, 50) }}</td>
                                <td>
                                    @if($log->user_id)
                                    <a href="{{ route('admin.block.user', $log->user_id) }}" class="btn btn-xs btn-danger">
                                        Block
                                    </a>
                                    @endif
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
@endsection

@push('after_scripts')
<script>
    // User Growth Chart
    new Chart(document.getElementById('userChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(range(1, 30)) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($this->getUserGrowthData()) !!},
                borderColor: '#3498db',
                tension: 0.1
            }]
        }
    });
</script>
@endpush