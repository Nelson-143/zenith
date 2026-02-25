@extends('layouts.tabler')

@section('title')
    {{ __('Your Team') }}
@endsection
@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between  mb-4">
        <div>
            <h2 class="page-title">{{ __('Team Management') }}</h2>
            <p class="text-muted mb-0">{{ __('Manage your team with a glance and ease') }}.</p>
            <div class="d-flex flex-row">
    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#userModal">
        <i class="ti ti-circle-plus"></i> {{ __('Add Team Member') }}
    </button>
    <a class="btn btn-primary" href="{{ route('admin.team.logs.show') }}">
        <i class="ti ti-eye"></i> {{ __('View Team Logs') }}
    </a>
</div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="ps-4">{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th class="text-end pe-4">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="align-middle">
                            <td class="ps-4">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-info">{{ $user->roles->pluck('name')->implode(', ') }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        {{ __('Actions') }}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-warning" href="#" onclick="editUser({{ json_encode($user) }})">
                                                <i class="ti ti-edit me-2"></i> {{ __('Edit') }}
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.team.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="ti ti-trash me-2"></i> {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- User Modal (Create / Edit) -->
<div class="modal modal-blur fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">{{ __('Add User') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" action="{{ route('admin.team.storeOrUpdate') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="user_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Password') }}</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Role') }}</label>
                        <select class="form-select" name="role" id="role" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="submitButton">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editUser(user) {
        document.getElementById('user_id').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('password').value = "";
        document.getElementById('role').value = user.roles[0]?.name || "";

        document.getElementById('userModalLabel').textContent = "Edit User";
        document.getElementById('submitButton').textContent = "Update User";

        var modal = new bootstrap.Modal(document.getElementById('userModal'));
        modal.show();
    }
</script>

@endsection