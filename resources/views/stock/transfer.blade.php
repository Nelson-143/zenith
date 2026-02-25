@extends('layouts.tabler')

@section('title')
    {{ __('Stock Transfer') }}
@endsection

@section('me')
    @parent
@endsection

@section('stocktrans')
<!-- Stock Transfer Page -->
<div class="page-body">
    <div class="container-xl">
        <!-- No Products Available Alert -->
        @if ($products->isEmpty())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-warning shadow-sm p-4 rounded" role="alert">
                        <h3 class="mb-3 text-center">No Products Available</h3>
                        <p class="text-muted text-center">
                            It seems there are no products available at the moment. Try adding new products later.
                        </p>
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <a href="{{ route('products.create') }}" class="btn btn-primary">{{ __('Add Product') }}</a>
                            <a href="{{ route('products.import.view') }}" class="btn btn-success">{{ __('Import Products') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <!-- Success Alert -->
                @if (session('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success shadow-sm p-4 rounded" role="alert">
                            <h3 class="mb-3">Success</h3>
                            <p>{{ session('success') }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <!-- Stock Transfer Table -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ __('Stock Transfer') }}</h4>
                            <div class="btn-group" role="group">
                                <a href="{{ route('products.create') }}" class="btn btn-primary me-2">{{ __('Add Product') }}</a>
                                <a href="{{ route('products.import.view') }}" class="btn btn-success">{{ __('Import Products') }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                        <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ __('Transfer Products') }}
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
<lord-icon
    src="https://cdn.lordicon.com/amfpjnmb.json"
    trigger="hover"
    style="width:50px;height:50px">
</lord-icon>
        </h4>

        <div class="btn-group">
            <div class="dropdown">
                <a href="#" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <x-icon.vertical-dots />
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="{{ route('stock.damaged') }}" class="dropdown-item">
                        <x-icon.plus /> {{ __('Create Damage Product') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Transfer Form -->
    <form method="POST" action="{{ route('stock.transfer') }}" class="card-body mb-0">
        @csrf
        <div class="row g-3">
        <div class="col-md-3">
    <label for="product_id" class="form-label">{{ __('Product') }}</label>
    <select name="product_id" class="form-select" required>
        @if ($products->isNotEmpty())
            @foreach ($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        @else
            <option value="" disabled selected>{{ __('No products available') }}</option>
        @endif
    </select>
</div>


<div class="col-md-3">
    <label for="from_location_id" class="form-label">{{ __('From Location') }}</label>
    <select name="from_location_id" class="form-select" required>
        @foreach(\App\Models\Location::where('account_id', auth()->user()->account_id)->get() as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-3">
    <label for="to_location_id" class="form-label">{{ __('To Location') }}</label>
    <select name="to_location_id" class="form-select" required>
        @foreach(\App\Models\Location::where('account_id', auth()->user()->account_id)->get() as $location)
            <option value="{{ $location->id }}">{{ $location->name }}</option>
        @endforeach
    </select>
</div>
            <div class="col-md-2">
                <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">{{ __('Transfer') }}</button>
            </div>
        </div>
    </form>

    <!-- Stock Transfers Table -->
   <!-- Stock Transfers Table -->
<div class="table-responsive card-table-container">
    <table class="table table-bordered table-hover datatable">
        <thead class="table-light">
            <tr>
                <th class="text-center">{{ __('No.') }}</th>
                <th class="text-center">{{ __('Product') }}</th>
                <th class="text-center">{{ __('From Location') }}</th>
                <th class="text-center">{{ __('To Location') }}</th>
                <th class="text-center">{{ __('Quantity') }}</th>
                <th class="text-center">{{ __('Date') }}</th>
                <th class="text-center">{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stockTransfers as $transfer)
                <tr>
                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                    <td class="align-middle text-center">{{ optional($transfer->product)->name ?? 'N/A' }}</td>
                    <td class="align-middle text-center">{{ $transfer->from_location ?? 'N/A' }}</td>
                    <td class="align-middle text-center">{{ $transfer->to_location ?? 'N/A' }}</td>
                    <td class="align-middle text-center">{{ $transfer->quantity ?? 0 }}</td>
                    <td class="align-middle text-center">{{ $transfer->created_at ? $transfer->created_at->format('Y-m-d') : 'N/A' }}</td>
                    <td class="align-middle text-center">
                        @if ($transfer && $transfer->id)
                            <x-button.delete class="btn-icon" 
                                route="{{ route('stock.transfer.delete', $transfer->id) }}"
                                onclick="return confirm('{{ __('Are you sure to delete this transfer?') }}')" />
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <p>{{ __('Sorry, no stock transfers found.') }}</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection