{{-- resources/views/admin/users/show.blade.php --}}
@extends(backpack_view('blank'))

@section('header')
    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">User Details</span>
            <small>{{ $user->name }}</small>
        </h2>
    </div>
@endsection

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Full Name:</label>
                            <p class="form-control">{{ $user->name }}</p>
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <p class="form-control">{{ $user->username }}</p>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <p class="form-control">{{ $user->email }}</p>
                        </div>
                        <div class="form-group">
                            <label>Role:</label>
                            <p class="form-control">
                                <span class="badge badge-{{ $user->role == 'SuperAdmin' ? 'danger' : ($user->role == 'Admin' ? 'warning' : 'info') }}">
                                    {{ $user->role }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status:</label>
                            <p class="form-control">
                                <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ $user->status }}
                                </span>
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Registered On:</label>
                            <p class="form-control">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div class="form-group">
                            <label>Last Login:</label>
                            <p class="form-control">{{ $user->last_login ? $user->last_login->format('Y-m-d H:i:s') : 'Never' }}</p>
                        </div>
                        <div class="form-group">
                            <label>Last IP:</label>
                            <p class="form-control">{{ $user->last_ip ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Business Information</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Business Name:</label>
                    <p class="form-control">{{ $user->store_name ?? 'Not provided' }}</p>
                </div>
                <div class="form-group">
                    <label>Business Address:</label>
                    <p class="form-control">{{ $user->store_address ?? 'Not provided' }}</p>
                </div>
                <div class="form-group">
                    <label>Business Phone:</label>
                    <p class="form-control">{{ $user->store_phone ?? 'Not provided' }}</p>
                </div>
                <div class="form-group">
                    <label>Business Email:</label>
                    <p class="form-control">{{ $user->store_email ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ url(config('backpack.base.route_prefix') . '/custom-users') }}" class="btn btn-default">
                <i class="la la-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>
@endsection