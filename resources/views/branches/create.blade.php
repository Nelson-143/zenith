@extends('layouts.tabler')

@section('title')
SetRStores|Branch
@endsection

@section('me')
    @parent
@endsection

@section('finassist')
<div class="container">
    <h2>Add New Branch</h2>
    <form action="{{ route('branches.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Branch Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Branch</button>
    </form>
</div>
@endsection