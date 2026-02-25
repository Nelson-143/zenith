@php
use app\Models\Product;
@endphp
@section('title', 'Orders Create')
<!--POS view-->
<div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('New Order') }}</h3>
                            <div class="ms-auto">
                                @for ($i = 1; $i <= 5; $i++)
                                    <button wire:click="switchTab({{ $i }})"
                                            class="btn btn-{{ $activeTab === $i ? 'success' : 'secondary' }} mx-1">
                                        Customer {{ $i }}
                                    </button>
                                @endfor
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="row gx-3 mb-3">
                                <div class="col-md-4">
                                    <label class="small my-1">{{ __('Date') }} <span class="text-danger">*</span></label>
                                    <input wire:model.live="purchaseDate" type="date" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="small mb-1">{{ __('Customer') }} <span class="text-danger">*</span></label>
                                    <input wire:model.live.debounce.300ms="searchCustomer" type="text" class="form-control" placeholder="Search customers...">
                                    <div class="custom-dropdown" style="position: relative;">
                                        @if(!empty($searchCustomer) && $filteredCustomers->isNotEmpty())
                                            <div class="custom-dropdown-content" style="position: absolute; z-index: 1000; background: white; width: 100%; border: 1px solid #ccc;">
                                                @foreach($filteredCustomers as $customer)
                                                    <div wire:click="$set('selectedCustomers.{{ $activeTab }}', '{{ $customer->id }}')"
                                                         class="custom-option" style="cursor: pointer; padding: 8px;">
                                                        {{ $customer->name }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <select wire:model.live="selectedCustomers.{{ $activeTab }}" class="form-control mt-2">
                                        <option value="pass_by">PASS BY</option>
                                        @foreach ($filteredCustomers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="small mb-1">{{ __('Reference') }}</label>
                                    <input type="text" class="form-control" value="ORD" readonly>
                                </div>
                            </div>
<h3>Cart (Tab {{ $activeTab }})</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Location</th>
            <th>Original Price</th>
            <th>Discounted Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($currentCart as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>
                    @if($item->options['shelf_product_id'])
                        Shelf
                    @else
                        @php
                            $locationId = $item->options['location_id'] ?? $this->locationSelections[$item->options['row_id']] ?? null;
                            $productLocation = \App\Models\ProductLocation::where('product_id', $item->id)
                                ->where('location_id', $locationId)
                                ->where('account_id', auth()->user()->account_id)
                                ->first();
                        @endphp
                        {{ $productLocation ? optional($productLocation->location)->name : 'N/A' }}
                    @endif
                </td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>
                    <input type="number" wire:model.live.debounce.500ms="cartDiscounts.{{ $item->rowId }}" 
                           min="0" step="0.01" class="form-control w-75" 
                           wire:change="updateDiscount('{{ $item->rowId }}', $event.target.value)">
                </td>
                <td>
                    <input type="number" wire:model.live.debounce.500ms="cartQty.{{ $item->rowId }}"
                           :value="old($cartQty[$item->rowId] ?? $item->qty, $item->qty)"
                           min="1" class="form-control w-75" 
                           wire:change="updateCart('{{ $item->rowId }}', $event.target.value)">
                </td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
                <td>
                    <button wire:click="removeFromCart('{{ $item->rowId }}')" class="btn btn-icon btn-danger btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M4 7l16 0" />
                            <path d="M10 11l0 6" />
                            <path d="M14 11l0 6" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-end">Subtotal</td>
            <td class="text-center">{{ number_format(Cart::instance('customer' . $activeTab)->subtotalFloat(), 2) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4" class="text-end">Tax ({{ $taxRate }}%)</td>
            <td class="text-center">{{ number_format($currentCart->sum(fn($item) => $item->options->tax * $item->qty), 2) }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="4" class="text-end">Total</td>
            <td class="text-center">{{ number_format(Cart::instance('customer' . $activeTab)->subtotalFloat() + $currentCart->sum(fn($item) => $item->options->tax * $item->qty), 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
                            
                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-outline-secondary mx-1" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    Add Custom Product
                                </button>
                                <button wire:click="createOrder" class="btn btn-success {{ $currentCart->count() > 0 ? '' : 'disabled' }}">
                                    {{ __('Create Invoice') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card mb-4 mb-xl-0">
                        <div class="card-header">
                            <h3 class="card-title">Product List</h3>
                            <div class="ms-auto">
                                <div class="btn-group">
                                    <button wire:click="toggleProductView('all')"
                                            class="btn btn-{{ $productView === 'all' ? 'primary' : 'outline-primary' }}">
                                        All Products
                                    </button>
                                    <button wire:click="toggleProductView('shelf')"
                                            class="btn btn-{{ $productView === 'shelf' ? 'primary' : 'outline-primary' }}">
                                        Shelf Products
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <input wire:model.live.debounce.500ms="searchProduct" type="text" class="form-control mb-3" placeholder="Search for products..." style="width: 100%;">
                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                                <table class="table table-striped table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($filteredProducts as $index => $product)
                                            @php
                                                $shelfProduct = $productView === 'shelf' ? $shelfProducts[$index] : null;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $product->name }}</td>
                                                <td class="text-center">
                                                    @if ($productView === 'shelf' && $shelfProduct)
                                                        <div class="input-group w-100">
                                                            <input type="number" step="0.01"
                                                                   wire:model.live.debounce.500ms="shelfQuantities.{{ $product->id }}"
                                                                   class="form-control">
                                                            <button wire:click="updateShelfStock({{ $product->id }}, {{ $shelfQuantities[$product->id] ?? 0 }})"
                                                                    class="btn btn-primary btn-sm">Update</button>
                                                        </div>
                                                        <small>(Current: {{ number_format($shelfProduct->quantity, 2) }})</small>
                                                    @else
                                                        {{ number_format($product->quantity, 2) }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($productView === 'shelf' && $shelfProduct)
                                                        {{ $shelfProduct->unit_name }}
                                                    @else
                                                        {{ $product->unit ? $product->unit->name : 'Piece' }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($productView === 'shelf' && $shelfProduct)
                                                        {{ number_format($shelfProduct->unit_price, 2) }}
                                                    @else
                                                        {{ number_format($product->selling_price, 2) }}
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($productView === 'shelf' && $shelfProduct)
                                                        <button wire:click="addToCart({{ $product->id }}, {{ $shelfProduct->id }})"
                                                                class="btn btn-icon btn-outline-primary">
                                                            <x-icon.cart/>
                                                        </button>
                                                    @else
                                                        <button wire:click="addToShelf({{ $product->id }})"
                                                                class="btn btn-icon btn-outline-secondary btn-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-category-plus">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                                                            </svg>
                                                        </button>
                                                        <button wire:click="addToCart({{ $product->id }})"
                                                                class="btn btn-icon btn-outline-primary">
                                                            <x-icon.cart/>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="text-center">No products found!</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for selecting location -->
@if($showLocationModal)
    <div class="modal modal-blur fade show" tabindex="-1" style="display: block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Location for {{ $products->find($currentProductId)->name }}</h5>
                    <button type="button" class="btn-close" wire:click="$set('showLocationModal', false)"></button>
                </div>
                <div class="modal-body">
                    @foreach($products->find($currentProductId)->productLocations as $productLocation)
                        <div class="form-check">
                            <input type="radio" class="form-check-input" 
                                   wire:model.live="locationSelections.{{ $currentProductId }}" 
                                   value="{{ $productLocation->location_id }}">
                            <label class="form-check-label">
                                {{ $productLocation->location->name }} (Stock: {{ $productLocation->quantity }})
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" wire:click="selectLocation({{ $currentProductId }})">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endif
  <div class="modal modal-blur fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Custom Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit="addCustomProduct">
                    <div class="mb-3">
                        <label for="customProductName" class="form-label">Product Name</label>
                        <input wire:model.live="customProductName" type="text" class="form-control" id="customProductName" required>
                        @error('customProductName') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="customProductQuantity" class="form-label">Quantity</label>
                        <input wire:model.live="customProductQuantity" type="number" class="form-control" id="customProductQuantity" min="1" required>
                        @error('customProductQuantity') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="customProductPrice" class="form-label">Price</label>
                        <input wire:model.live="customProductPrice" type="number" class="form-control" id="customProductPrice" min="0" step="0.01" required>
                        @error('customProductPrice') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="customLocationId" class="form-label">Location</label>
                        <select wire:model.live="customLocationId" class="form-select" id="customLocationId" required>
                            <option value="">Select Location</option>
                            @foreach(\App\Models\Location::where('account_id', auth()->user()->account_id)->get() as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('customLocationId') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

