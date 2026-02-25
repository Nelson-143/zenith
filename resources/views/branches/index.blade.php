@extends('layouts.tabler')
@section('title')
   {{ __(' Branch') }}
@endsection

@section('content')
<div class="container">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h2 class="page-title">{{ __('Branch Management') }}</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#branchModal" @click="resetForm">
            <i class="ti ti-plus"></i> {{ __('Add Branch') }}
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
    @foreach ($branches as $branch)
        @if ($branch->account_id == auth()->user()->account_id)
            <tr>
                <td>{{ $branch->name }}</td>
                <td>
                    <span class="badge {{ $branch->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($branch->status) }}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            {{ __('Actions') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item text-warning" href="#" onclick="editBranch({{ json_encode($branch) }})">
                                    <i class="ti ti-edit"></i> {{ __('Edit') }}
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ti ti-trash"></i> {{ __('Disable') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endif
    @endforeach
</tbody>

            </table>
        </div>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- Branch Modal -->
<div class="modal modal-blur fade" id="branchModal" tabindex="-1" aria-labelledby="branchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="branchModalLabel">{{ __('Add Branch') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="branchForm" action="{{ route('branches.store') }}" method="POST">
    @csrf
    <input type="hidden" name="branch_id" id="branch_id">

    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">{{ __('Branch Name') }}</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Status') }}</label>
            <select class="form-select" name="status" id="status">
                <option value="active">{{ __('Active') }}</option>
                <option value="disabled">{{ __('Disabled') }}</option>
            </select>
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
<script>
    function editBranch(branch) {
        document.getElementById('branch_id').value = branch.id;
        document.getElementById('name').value = branch.name;
        document.getElementById('status').value = branch.status;

        document.getElementById('branchModalLabel').textContent = "Edit Branch";
        var modal = new bootstrap.Modal(document.getElementById('branchModal'));
        modal.show();
    }
</script>


@endsection
