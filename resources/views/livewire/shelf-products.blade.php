@section('title', 'Manage Shelf Products')
<div>
    <!--Shelf view-->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Manage Shelf Products') }}</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#logsModal">
                            {{ __('View Logs') }}
                        </button>
                        <a href="{{ route('pos.index') }}" class="btn btn-secondary">
                            {{ __('Back to POS') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="mb-3">
                        <h4>{{ __('Add New Shelf Product') }}</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Product') }}</label>
                                <select wire:model.live="newShelfProduct.product_id" class="form-select">
                                    <option value="">{{ __('Select a product') }}</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('newShelfProduct.product_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">{{ __('Unit Name') }}</label>
                                <input type="text" wire:model.live="newShelfProduct.unit_name" class="form-control">
                                @error('newShelfProduct.unit_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">{{ __('Unit Price') }}</label>
                                <input type="number" step="0.01" wire:model.live="newShelfProduct.unit_price" class="form-control">
                                @error('newShelfProduct.unit_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">{{ __('Conversion Factor') }}</label>
                                <input type="number" step="0.01" wire:model.live="newShelfProduct.conversion_factor" class="form-control">
                                @error('newShelfProduct.conversion_factor')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">{{ __('Quantity') }}</label>
                                <input type="number" step="0.01" wire:model.live="newShelfProduct.quantity" class="form-control">
                                @error('newShelfProduct.quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-1 d-flex align-items-end">
                                <button wire:click="addShelfProduct" class="btn btn-primary">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Name</th>
                                    <th>Unit Price</th>
                                    <th>Conversion Factor</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($shelfProducts as $id => $shelfProduct)
    <tr wire:key="shelf-product-{{ $id }}">
        <td>{{ $shelfProduct['product']['name'] ?? 'N/A' }}</td>
        <td>
            <input type="text" 
                   wire:model.live="shelfProducts.{{ $id }}.unit_name"
                   class="form-control">
            @error("shelfProducts.{$id}.unit_name")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </td>
        <td>
            <input type="number" step="0.01"
                   wire:model.live.debounce.500ms="shelfProducts.{{ $id }}.unit_price"
                   class="form-control">
            @error("shelfProducts.{$id}.unit_price")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </td>
        <td>
            <input type="number" step="0.01"
                   wire:model.live.debounce.500ms="shelfProducts.{{ $id }}.conversion_factor"
                   class="form-control">
            @error("shelfProducts.{$id}.conversion_factor")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </td>
        <td>
            <input type="number" step="0.01"
                   wire:model.live.debounce.500ms="shelfProducts.{{ $id }}.quantity"
                   class="form-control">
            @error("shelfProducts.{$id}.quantity")
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </td>
        <td>
            <button wire:click="updateShelfProduct({{ $id }})" 
                    class="btn btn-primary btn-sm me-1">
                Update
            </button>
            <button wire:click="removeShelfProduct({{ $id }})" 
                    class="btn btn-danger btn-sm">
                Remove
            </button>
        </td>
    </tr>
@endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Debugging Output -->
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Modal -->
    <div class="modal modal-blur fade" id="logsModal" tabindex="-1" aria-labelledby="logsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logsModalLabel">{{ __('Shelf Stock Logs') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity Change</th>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>Notes</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ $log->shelfProduct->product->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($log->quantity_change, 2) }}</td>
                                        <td>{{ ucfirst($log->action) }}</td>
                                        <td>{{ $log->user->name ?? 'Unknown' }}</td>
                                        <td>{{ $log->notes }}</td>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No logs found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
