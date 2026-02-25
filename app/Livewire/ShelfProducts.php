<?php

namespace app\Livewire;
use Livewire\Component;
use app\Models\Product;
use app\Models\ShelfProduct;
use app\Models\ShelfStockLog;
use Illuminate\Support\Facades\Log;
//shelfs
class ShelfProducts extends Component
{
    public $products;
    public $shelfProducts;
    public $newShelfProduct = [
        'product_id' => '',
        'unit_name' => '',
        'unit_price' => '',
        'conversion_factor' => 1,
        'quantity' => 0,
    ];
    public $logs;

    protected $rules = [
        'newShelfProduct.product_id' => 'required|exists:products,id',
        'newShelfProduct.unit_name' => 'required|string|max:255',
        'newShelfProduct.unit_price' => 'required|numeric|min:0',
        'newShelfProduct.conversion_factor' => 'required|numeric|min:0',
        'newShelfProduct.quantity' => 'required|numeric|min:0',
        'shelfProducts.*.unit_name' => 'required|string|max:255',
        'shelfProducts.*.unit_price' => 'required|numeric|min:0',
        'shelfProducts.*.conversion_factor' => 'required|numeric|min:0',
        'shelfProducts.*.quantity' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->loadData();
        $this->loadLogs();
        Log::info('ShelfProducts mounted', ['shelfProducts' => $this->shelfProducts]);
    }

    public function loadData()
    {
        $this->products = Product::where('account_id', auth()->user()->account_id)->get();
        $this->shelfProducts = ShelfProduct::where('account_id', auth()->user()->account_id)
            ->with('product')
            ->get()
            ->mapWithKeys(function ($shelfProduct) {
                return [
                    $shelfProduct->id => [
                        'id' => $shelfProduct->id,
                        'product_id' => $shelfProduct->product_id,
                        'unit_name' => $shelfProduct->unit_name,
                        'unit_price' => $shelfProduct->unit_price,
                        'conversion_factor' => $shelfProduct->conversion_factor,
                        'quantity' => $shelfProduct->quantity,
                        'product' => $shelfProduct->product,
                    ]
                ];
            })->toArray();
    }

    public function loadLogs()
    {
        $this->logs = ShelfStockLog::where('account_id', auth()->user()->account_id)
            ->with(['shelfProduct.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function addShelfProduct()
    {
        $this->validateOnly('newShelfProduct');

        $shelfProduct = ShelfProduct::create([
            'product_id' => $this->newShelfProduct['product_id'],
            'unit_name' => $this->newShelfProduct['unit_name'],
            'unit_price' => $this->newShelfProduct['unit_price'],
            'conversion_factor' => $this->newShelfProduct['conversion_factor'],
            'quantity' => $this->newShelfProduct['quantity'],
            'account_id' => auth()->user()->account_id,
        ]);

        ShelfStockLog::create([
            'shelf_product_id' => $shelfProduct->id,
            'quantity_change' => $this->newShelfProduct['quantity'],
            'action' => 'add',
            'user_id' => auth()->id(),
            'account_id' => auth()->user()->account_id,
            'notes' => 'Added shelf product via manage shelf products',
        ]);

        $this->newShelfProduct = [
            'product_id' => '',
            'unit_name' => '',
            'unit_price' => '',
            'conversion_factor' => 1,
            'quantity' => 0,
        ];

        $this->loadData();
        $this->loadLogs();
        session()->flash('success', 'Shelf product added successfully.');
    }

    public function updateShelfProduct($id)
    {
        try {
            $this->validateOnly("shelfProducts.{$id}");

            $shelfProduct = ShelfProduct::where('account_id', auth()->user()->account_id)
                ->where('id', $id)
                ->first();

            if ($shelfProduct) {
                $oldQuantity = $shelfProduct->quantity;
                $shelfProduct->update([
                    'unit_name' => $this->shelfProducts[$id]['unit_name'],
                    'unit_price' => $this->shelfProducts[$id]['unit_price'],
                    'conversion_factor' => $this->shelfProducts[$id]['conversion_factor'],
                    'quantity' => $this->shelfProducts[$id]['quantity'],
                ]);

                if ($this->shelfProducts[$id]['quantity'] != $oldQuantity) {
                    ShelfStockLog::create([
                        'shelf_product_id' => $shelfProduct->id,
                        'quantity_change' => $this->shelfProducts[$id]['quantity'] - $oldQuantity,
                        'action' => 'update',
                        'user_id' => auth()->id(),
                        'account_id' => auth()->user()->account_id,
                        'notes' => 'Updated shelf stock via manage shelf products',
                    ]);
                }

                $this->loadData();
                $this->loadLogs();
                session()->flash('success', 'Shelf product updated successfully.');
            } else {
                session()->flash('error', 'Shelf product not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating shelf product: ' . $e->getMessage());
            session()->flash('error', 'Failed to update shelf product: ' . $e->getMessage());
        }
    }

    public function removeShelfProduct($id)
    {
        try {
            $shelfProduct = ShelfProduct::where('account_id', auth()->user()->account_id)
                ->where('id', $id)
                ->first();

            if ($shelfProduct) {
                ShelfStockLog::create([
                    'shelf_product_id' => $shelfProduct->id,
                    'quantity_change' => -$shelfProduct->quantity,
                    'action' => 'remove',
                    'user_id' => auth()->id(),
                    'account_id' => auth()->user()->account_id,
                    'notes' => 'Removed shelf product via manage shelf products',
                ]);

                $shelfProduct->delete();
                $this->loadData();
                $this->loadLogs();
                session()->flash('success', 'Shelf product removed successfully.');
            } else {
                session()->flash('error', 'Shelf product not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error removing shelf product: ' . $e->getMessage());
            session()->flash('error', 'Failed to remove shelf product: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.shelf-products')
            ->extends('layouts.tabler')
            ->section('content');
    }
}