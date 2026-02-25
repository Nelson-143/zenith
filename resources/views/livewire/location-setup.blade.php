<div class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Location Setup</h2>
                    <p class="text-muted">Define your locations and assign product quantities.</p>
                </div>
            </div>
        </div>

        <div class="row row-cards">
            <!-- Locations Section -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Locations</h3>
                    </div>
                    <div class="card-body">
                        @foreach($locations as $index => $location)
                            <div class="row align-items-center mb-2">
                                <div class="col-md-8">
                                    <input wire:model.live="locations.{{ $index }}.name" class="form-control" placeholder="Location Name" required>
                                    @error("locations.$index.name")
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="checkbox" wire:model.live="locations.{{ $index }}.is_default" class="form-check-input">
                                        <label class="form-check-label">Set as Default</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" wire:click="removeLocation({{ $index }})" class="btn btn-danger" wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M4 7l16 0" />
                                            <path d="M10 11l0 6" />
                                            <path d="M14 11l0 6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7l-1 -4" />
                                            <path d="M15 7l1 -4" />
                                        </svg>
                                    </button>
                                    @if($index == count($locations) - 1)
                                        <button type="button" wire:click="addLocation" class="btn btn-secondary ms-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5l0 14" />
                                                <path d="M5 12l14 0" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Quantities Section -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assign Product Quantities</h3>
                    </div>
                    <div class="card-body">
                        @foreach($products as $product)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }} (Original Total: {{ $product->quantity }})</h5>
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <thead>
                                                <tr>
                                                    <th>Location</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($locations as $location)
                                                    @php
                                                        $locationId = $location['id'] ?? null;
                                                        $quantity = $productQuantities[$product->id][$locationId] ?? 0;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $location['name'] ?? 'New Location' }}</td>
                                                        <td>
                                                            <input wire:model.live="productQuantities.{{ $product->id }}.{{ $locationId }}" type="number" class="form-control" min="0" required>
                                                            @error("productQuantities.$product->id.$locationId")
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Overview Section (After Setup) -->
            @if(auth()->user()->account->is_location_setup)
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Product Location Overview <span class="badge bg-success">Last Updated by {{ auth()->user()->name }}</span></h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            @foreach($locations as $location)
                                                <th>{{ $location['name'] ?? 'New Location' }}</th>
                                            @endforeach
                                            <th>Total Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                @foreach($locations as $location)
                                                    @php
                                                        $locationId = $location['id'] ?? null;
                                                        $quantity = $productQuantities[$product->id][$locationId] ?? 0;
                                                    @endphp
                                                    <td>{{ $quantity }}</td>
                                                @endforeach
                                                <td>{{ array_sum($productQuantities[$product->id] ?? []) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Save Button -->
            <div class="col-12">
                <div class="card-footer text-end">
                    <button wire:click="saveSetup" class="btn btn-primary" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                            <path d="M12 14h-2a2 2 0 0 1 -2 -2v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-2" />
                            <path d="M14 12v.01" />
                        </svg>
                        Save Setup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>