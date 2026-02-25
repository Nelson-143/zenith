@extends('layouts.tabler')

@section('title')
SetRStores|Branch
@endsection

@section('me')
    @parent
@endsection

@section('finassist')
<div class="container">
    <h2>Edit Branch</h2>
    <form action="{{ route('branches.update', $branch->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Branch Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $branch->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Branch</button>
    </form>
</div>
@endsection