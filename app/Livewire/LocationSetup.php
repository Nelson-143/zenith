<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use App\Models\Product;
use App\Models\ProductLocation;
use Illuminate\Support\Facades\Log;

class LocationSetup extends Component
{
    public $locations = [];
    public $products;
    public $productQuantities = [];
    public $isSetupComplete = false;

    public function mount()
    {
        // Load existing locations
        $this->locations = Location::where('account_id', auth()->user()->account_id)->get()->map(function ($location) {
            return ['id' => $location->id, 'name' => $location->name, 'is_default' => $location->is_default];
        })->toArray();

        if (empty($this->locations)) {
            $this->addLocation(); // Add a default new location if none exist
        }

        // Load products
        $this->products = Product::where('account_id', auth()->user()->account_id)->get();

        // Load existing product quantities
        foreach ($this->products as $product) {
            $this->productQuantities[$product->id] = [];
            $productLocations = ProductLocation::where('product_id', $product->id)
                ->where('account_id', auth()->user()->account_id)
                ->get()
                ->pluck('quantity', 'location_id')
                ->toArray();
            foreach ($this->locations as $location) {
                $locationId = $location['id'];
                $this->productQuantities[$product->id][$locationId] = $productLocations[$locationId] ?? 0;
            }
        }

        $this->isSetupComplete = auth()->user()->account->is_location_setup ?? false;
    }

    public function addLocation()
    {
        $this->locations[] = ['id' => null, 'name' => '', 'is_default' => false];
    }

    public function removeLocation($index)
    {
        unset($this->locations[$index]);
        $this->locations = array_values($this->locations);
    }

    public function saveSetup()
    {
        $this->validate([
            'locations.*.name' => 'required|string|max:255',
        ]);

        foreach ($this->locations as $index => $location) {
            $locationModel = Location::updateOrCreate(
                ['id' => $location['id'] ?? null, 'account_id' => auth()->user()->account_id],
                [
                    'name' => $location['name'],
                    'is_default' => $location['is_default'] ?? false,
                ]
            );
            $this->locations[$index]['id'] = $locationModel->id;
        }

        foreach ($this->products as $product) {
            foreach ($this->locations as $location) {
                $locationId = $location['id'];
                $quantity = $this->productQuantities[$product->id][$locationId] ?? 0;
                ProductLocation::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'location_id' => $locationId,
                        'account_id' => auth()->user()->account_id,
                    ],
                    ['quantity' => $quantity]
                );
            }
            // Update product total quantity
            $product->quantity = array_sum($this->productQuantities[$product->id] ?? []);
            $product->save();
        }

        auth()->user()->account->update(['is_location_setup' => true]);
        $this->isSetupComplete = true;

        session()->flash('success', 'Location setup saved successfully! Last updated by ' . auth()->user()->name);
        Log::info('Location setup saved', ['user_id' => auth()->id(), 'locations' => $this->locations]);
    }

    public function render()
    {
        return view('livewire.location-setup', [
            'locations' => $this->locations,
            'products' => $this->products,
        ])->extends('layouts.tabler')->section('content');
    }
}
