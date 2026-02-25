@extends('layouts.tabler')

@section('title')
    Damaged Products{{ __('') }}
@endsection
@section('me')
    @parent
@endsection

@section('Damage')

<!--- try me --->
<div class="page-body">
        <div class="container-xl">
            @if ($products->isEmpty())
            <div class="alert alert-warning">
                <h3 class="mb-1">No products available</h3>
                <p>It seems there are no products available at the moment. Try adding new products later.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                <div style="padding-top:10px; text-align: center;">
                    <a href="{{ route('products.import.view') }}" class="btn btn-primary">Import Products</a>
                </div>
            </div>
            @else

            <div class="container-xl">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <h3 class="mb-1">Success</h3>
                        <p>{{ session('success') }}</p>

                        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                    </div>
                @endif
                <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">{{ __('Damaged Products') }}
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
<lord-icon
    src="https://cdn.lordicon.com/peulpjhz.json"
    trigger="hover"
    style="width:50px;height:50px">
</lord-icon>
        </h4>
    </div>

    <!-- Damaged Products Form -->
    <form method="POST" action="{{ route('stock.damaged.post') }}" class="card-body mb-0">
        @csrf
        <div class="row g-3">
        <div class="col-md-3">
            <label for="product_id" class="form-label">{{ __('Product') }}</label>
            <select name="product_id" class="form-select" required>
                @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ __('Remaining') }}: {{ $product->remaining_quantity }})
                        </option>
                    @endforeach
                @else
                    <option value="" disabled selected>{{ __('No products available') }}</option>
                @endif
            </select>
        </div>
            <div class="col-md-3">
                <label for="location" class="form-label">{{ __('Location') }}</label>
                <input type="text" name="location" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                <input type="number" name="quantity" class="form-control" min="1" required>
            </div>
            <div class="col-md-3">
                <label for="reason" class="form-label">{{ __('Reason') }}</label>
                <input type="text" name="reason" class="form-control">
            </div>
            <div class="col-md-12 d-flex align-items-end mt-3">
                <button type="submit" class="btn btn-primary w-100">{{ __('Record Damaged Product') }}</button>
            </div>
        </div>
    </form>

    <!-- Damaged Products Table -->
    <div class="table-responsive card-table-container">
        <table class="table table-bordered table-hover datatable">
            <thead class="table-light">
                <tr>
                    <th class="text-center">{{ __('No.') }}</th>
                    <th class="text-center">{{ __('Product') }}</th>
                    <th class="text-center">{{ __('Location') }}</th>
                    <th class="text-center">{{ __('Quantity') }}</th>
                    <th class="text-center">{{ __('Reason') }}</th>
                    <th class="text-center">{{ __('Date') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($damagedProducts ?? [] as $damaged)
                    <tr>
                        <td class="align-middle text-center">{{ $loop->iteration }}</td>
                        <td class="align-middle text-center">{{ $damaged->product->name }}</td>
                        <td class="align-middle text-center">{{ $damaged->location }}</td>
                        <td class="align-middle text-center">{{ $damaged->quantity }}</td>
                        <td class="align-middle text-center">{{ $damaged->reason ?? 'N/A' }}</td>
                        <td class="align-middle text-center">{{ $damaged->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-mood-sad">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M9 10l.01 0" />
                                <path d="M15 10l.01 0" />
                                <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0" />
                            </svg>
                            <p class="mt-2">{{ __('Sorry, no damaged products found.') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

            </div>
        @endif
        </div>
    </div>
@endsection