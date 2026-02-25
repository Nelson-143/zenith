<?php

namespace app\Livewire;
use Livewire\Component;
use app\Models\Product;
use app\Models\Customer;

use app\Models\ProductLocation;
use app\Models\ShelfProduct;
use app\Models\ShelfStockLog;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
//oder POS
class CreateOrder extends Component
{
    public $products;
    public $shelfProducts;
    public $customers;
    public $activeTab = 1;
    public $purchaseDate;
    public $selectedCustomers = [];
    public $searchProduct = '';
    public $searchCustomer = '';
    public $showModal = false;
    public $customProductName;
    public $customProductQuantity;
    public $customProductPrice;
    public float $taxRate;
    public $shelfQuantities = [];
    public $productView = 'all'; // 'all' or 'shelf'
    public $cartQty = [];
    public $locationSelections = []; // Store location selections for cart items
    public $showLocationModal = false;
    public $currentProductId;
    public $cartDiscounts = []; // Store discounted prices per cart item
    public $customLocationId; // Location for custom products
    public $customProducts = [];
    public $currentShelfProductId;

 public function mount()
{
    $this->loadProducts();
    $this->customers = Customer::where('account_id', auth()->user()->account_id)->get()->sortBy('name');
    $this->purchaseDate = now()->format('Y-m-d');
    $this->taxRate = auth()->user()->account->tax_rate ?? 0;
    for ($i = 1; $i <= 5; $i++) {
        $this->selectedCustomers[$i] = 'pass_by';
        Cart::instance('customer' . $i)->destroy(); // Clear cart on mount
    }
    session()->forget('pending_order'); // Clear pending order session
    foreach ($this->shelfProducts as $shelfProduct) {
        $this->shelfQuantities[$shelfProduct->product_id] = $shelfProduct->quantity;
    }
    foreach (Cart::instance('customer' . $this->activeTab)->content() as $item) {
        $this->cartQty[$item->rowId] = $item->qty;
        $this->cartDiscounts[$item->rowId] = $item->price;
    }
    Log::info('CreateOrder mounted', ['user_id' => auth()->id(), 'tax_rate' => $this->taxRate]);
}

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->searchProduct = '';
        $this->searchCustomer = '';
    }

    public function toggleProductView($view)
    {
        $this->productView = $view;
        $this->searchProduct = '';
        $this->loadProducts();
    }

    public function updatedSearchProduct()
    {
        $this->loadProducts();
    }

    public function updateShelfStock($productId, $quantity)
    {
        if ($quantity < 0) {
            session()->flash('error', 'Shelf stock cannot be negative.');
            return;
        }
        $shelfProduct = ShelfProduct::where('product_id', $productId)
            ->where('account_id', auth()->user()->account_id)
            ->first();
        if ($shelfProduct) {
            $oldQuantity = $shelfProduct->quantity;
            $shelfProduct->update(['quantity' => $quantity]);
            $this->shelfQuantities[$productId] = $quantity;
            ShelfStockLog::create([
                'shelf_product_id' => $shelfProduct->id,
                'quantity_change' => $quantity - $oldQuantity,
                'action' => 'update',
                'user_id' => auth()->id(),
                'account_id' => auth()->user()->account_id,
                'notes' => 'Updated shelf stock in POS',
            ]);
            session()->flash('success', 'Shelf stock updated for ' . $shelfProduct->product->name);
            $this->loadProducts();
        }
    }

    public function addToShelf($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $shelfProduct = ShelfProduct::firstOrCreate(
                [
                    'product_id' => $productId,
                    'account_id' => auth()->user()->account_id,
                ],
                [
                    'unit_name' => 'Piece',
                    'unit_price' => $product->selling_price,
                    'conversion_factor' => 1,
                    'quantity' => 0,
                ]
            );
            ShelfStockLog::create([
                'shelf_product_id' => $shelfProduct->id,
                'quantity_change' => 0,
                'action' => 'add',
                'user_id' => auth()->id(),
                'account_id' => auth()->user()->account_id,
                'notes' => 'Added to shelf in POS',
            ]);
            session()->flash('success', $product->name . ' added to shelf.');
            $this->loadProducts();
        }
    }

 public function addToCart($productId, $shelfProductId = null)
{
    $product = Product::with('productLocations')->find($productId);
    if (!$product) {
        session()->flash('error', 'Product not found.');
        return;
    }

    if ($this->productView === 'shelf' && $shelfProductId) {
        $shelfProduct = ShelfProduct::find($shelfProductId);
        if (!$shelfProduct) {
            session()->flash('error', 'Shelf product not found.');
            return;
        }
        if ($shelfProduct->quantity < 1) {
            session()->flash('error', 'Not enough shelf stock for ' . $product->name);
            return;
        }
        $unitPrice = $shelfProduct->unit_price;
        $unitName = $shelfProduct->unit_name;
        $conversionFactor = $shelfProduct->conversion_factor;
        $locationId = null;
    } else {
        $unitPrice = $product->selling_price;
        $unitName = $product->unit ? $product->unit->name : 'Piece';
        $conversionFactor = 1;
        // Only check stock, do not deduct
        if ($product->productLocations->sum('quantity') < 1) {
            session()->flash('error', 'Not enough store stock for ' . $product->name);
            return;
        }
        if ($product->productLocations->count() > 1) {
            $this->currentProductId = $productId;
            $this->currentShelfProductId = null;
            $this->showLocationModal = true;

            $this->locationSelections[$productId] = null;
            Log::info('Showing location modal for product', [
                'product_id' => $productId,
                'locations' => $product->productLocations->toArray(),
            ]);
            return;
        }
        $defaultLocation = $product->productLocations->first();
        if (!$defaultLocation) {
            session()->flash('error', 'No location assigned for ' . $product->name);
            return;
        }
        $locationId = $defaultLocation->location_id;
        $productLocation = ProductLocation::where('product_id', $productId)
            ->where('location_id', $locationId)
            ->where('account_id', auth()->user()->account_id)
            ->first();
        if ($productLocation && $productLocation->quantity < 1) {
            session()->flash('error', 'Not enough stock in ' . $defaultLocation->location->name . ' for ' . $product->name);
            return;
        }
    }

    $tax = $unitPrice * ($this->taxRate / 100);
    $rowId = uniqid();
    Cart::instance('customer' . $this->activeTab)->add([
        'id' => $product->id,
        'name' => $product->name . ' (' . $unitName . ')',
        'qty' => 1,
        'price' => $unitPrice,
        'weight' => 1,
        'options' => [
            'tax' => $tax,
            'shelf_product_id' => $shelfProductId,
            'conversion_factor' => $conversionFactor,
            'location_id' => $locationId,
            'row_id' => $rowId,
        ],
    ]);
    $this->locationSelections[$rowId] = $locationId;
    $this->cartDiscounts[$rowId] = $unitPrice;
    session()->flash('success', 'Product added to cart!');
}
public function selectLocation($productId)
{
    Log::info('selectLocation called', [
        'product_id' => $productId,
        'locationSelections' => $this->locationSelections,
    ]);

    $product = Product::with('productLocations')->find($productId);
    if (!$product) {
        session()->flash('error', 'Product not found.');
        return;
    }

    // Ensure the selected location ID is set correctly
    $selectedLocationId = $this->locationSelections[$productId] ?? null;
    if (!$selectedLocationId) {
        session()->flash('error', 'Please select a location.');
        return;
    }

    $productLocation = $product->productLocations->where('location_id', $selectedLocationId)->first();
    if (!$productLocation) {
        session()->flash('error', 'Selected location not found for this product.');
        return;
    }

    Log::info('Checking stock for location', [
        'location_id' => $selectedLocationId,
        'quantity' => $productLocation->quantity,
    ]);

    if ($productLocation->quantity < 1) {
        session()->flash('error', 'Selected location has no stock for this product. Available stock: ' . $productLocation->quantity);
        return;
    }

    $unitPrice = $product->selling_price;
    $unitName = $product->unit ? $product->unit->name : 'Piece';
    $conversionFactor = 1;
    $tax = $unitPrice * ($this->taxRate / 100);
    $rowId = uniqid();

    Cart::instance('customer' . $this->activeTab)->add([
        'id' => $product->id,
        'name' => $product->name . ' (' . $unitName . ')',
        'qty' => 1,
        'price' => $unitPrice,
        'weight' => 1,
        'options' => [
            'tax' => $tax,
            'shelf_product_id' => $this->currentShelfProductId,
            'conversion_factor' => $conversionFactor,
            'location_id' => $selectedLocationId,
            'row_id' => $rowId,
        ],
    ]);
    $this->locationSelections[$rowId] = $selectedLocationId;
    $this->cartDiscounts[$rowId] = $unitPrice;
    $this->showLocationModal = false;
    $this->currentProductId = null;
    $this->currentShelfProductId = null;
    session()->flash('success', 'Product added to cart!');
}
public function updateCart($rowId, $qty)
{
    $cartItem = Cart::instance('customer' . $this->activeTab)->get($rowId);
    if ($cartItem) {
        $product = Product::with('productLocations')->find($cartItem->id);
        $requiredStock = $qty * ((float) ($cartItem->options['conversion_factor'] ?? 1));
        if ($cartItem->options['shelf_product_id']) {
            $shelfProduct = ShelfProduct::find($cartItem->options['shelf_product_id']);
            if ($shelfProduct && $shelfProduct->quantity < $requiredStock) {
                session()->flash('error', 'Not enough shelf stock for ' . $product->name);
                $this->cartQty[$rowId] = $cartItem->qty; // Revert on error
                return;
            }
        } else {
            $locationId = $this->locationSelections[$cartItem->options['row_id']] ?? $cartItem->options['location_id'];
            $productLocation = $product->productLocations->where('location_id', $locationId)->first();
            if (!$productLocation || $productLocation->quantity < $requiredStock) {
                session()->flash('error', 'Not enough stock in selected location for ' . $product->name);
                $this->cartQty[$rowId] = $cartItem->qty; // Revert on error
                return;
            }
        }
        $tax = $cartItem->price * ($this->taxRate / 100);
        Cart::instance('customer' . $this->activeTab)->update($rowId, [
            'qty' => $qty,
            'options' => [
                'tax' => $tax,
                'shelf_product_id' => $cartItem->options['shelf_product_id'],
                'conversion_factor' => $cartItem->options['conversion_factor'],
                'location_id' => $cartItem->options['location_id'],
                'row_id' => $cartItem->options['row_id'],
            ],
        ]);
        $this->cartQty[$rowId] = $qty; // Sync the component state
        session()->flash('success', 'Cart updated!');
    }
}
public function removeFromCart($rowId)
{
    $cartItem = Cart::instance('customer' . $this->activeTab)->get($rowId);
    if ($cartItem) {
        // No stock adjustment needed since stock is deducted only on order finalization
        Cart::instance('customer' . $this->activeTab)->remove($rowId);
        unset($this->cartQty[$rowId]);
        unset($this->cartDiscounts[$rowId]);
        unset($this->locationSelections[$rowId]);
        session()->flash('success', 'Product removed from cart!');
    }
}

 public function addCustomProduct()
{
    $this->validate([
        'customProductName' => 'required|string|max:255',
        'customProductQuantity' => 'required|numeric|min:1',
        'customProductPrice' => 'required|numeric|min:0',
        'customLocationId' => 'required|exists:locations,id',
    ]);

    $tax = $this->customProductPrice * ($this->taxRate / 100);
    $rowId = uniqid();

    // Add the custom product to the cart
    Cart::instance('customer' . $this->activeTab)->add([
        'id' => $rowId,
        'name' => $this->customProductName,
        'qty' => $this->customProductQuantity,
        'price' => $this->customProductPrice,
        'weight' => 1,
        'options' => [
            'tax' => $tax,
            'shelf_product_id' => null,
            'conversion_factor' => 1,
            'location_id' => $this->customLocationId,
            'row_id' => $rowId,
            'is_custom' => true,
        ],
    ]);

    // Initialize cartQty and cartDiscounts for the new item
    $this->cartQty[$rowId] = $this->customProductQuantity;
    $this->cartDiscounts[$rowId] = $this->customProductPrice;

    // Save custom product details for order processing
    $this->customProducts[$rowId] = [
        'name' => $this->customProductName,
        'quantity' => $this->customProductQuantity,
        'price' => $this->customProductPrice,
        'location_id' => $this->customLocationId,
        'account_id' => auth()->user()->account_id,
    ];

    // Reset form fields
    $this->reset(['customProductName', 'customProductQuantity', 'customProductPrice', 'customLocationId']);

    session()->flash('success', 'Custom product added to cart!');
}
public function createOrder()
{
    $currentCart = Cart::instance('customer' . $this->activeTab)->content();
    if ($currentCart->isEmpty()) {
        session()->flash('error', 'Cart is empty.');
        return;
    }

    // Validate stock availability without deducting
    foreach ($currentCart as $item) {
        if (isset($item->options['is_custom']) && $item->options['is_custom']) {
            continue;
        }

        $product = Product::with('productLocations')->find($item->id);
        $requiredStock = $item->qty * ($item->options['conversion_factor'] ?? 1);
        if ($item->options['shelf_product_id']) {
            $shelfProduct = ShelfProduct::find($item->options['shelf_product_id']);
            if ($shelfProduct && $shelfProduct->quantity < $requiredStock) {
                session()->flash('error', 'Not enough shelf stock for ' . $item->name);
                return;
            }
        } else {
            $locationId = $this->locationSelections[$item->options['row_id']] ?? $item->options['location_id'];
            $productLocation = $product->productLocations->where('location_id', $locationId)->first();
            if (!$productLocation || $productLocation->quantity < $requiredStock) {
                session()->flash('error', 'Not enough stock in selected location for ' . $item->name);
                return;
            }
        }
    }

    $subTotal = Cart::instance('customer' . $this->activeTab)->subtotalFloat();
    $vat = 0;
    $discountAmount = 0;
    foreach ($currentCart as $item) {
        $vat += $item->options['tax'] * $item->qty;
        $originalPrice = $this->cartDiscounts[$item->rowId] ?? $item->price;
        $discountAmount += ($originalPrice - $item->price) * $item->qty;
    }
    $total = $subTotal + $vat;

    // Store the cart in session without deducting stock
    session()->put('pending_order', [
        'cart' => $currentCart->toArray(),
        'customer_id' => $this->selectedCustomers[$this->activeTab],
        'sub_total' => $subTotal,
        'vat' => $vat,
        'discount_amount' => $discountAmount,
        'total' => $total,
        'active_tab' => $this->activeTab,
        'tax_rate' => $this->taxRate,
        'custom_products' => $this->customProducts,
    ]);

    Log::info('Redirecting to invoices.create', [
        'sub_total' => $subTotal,
        'vat' => $vat,
        'discount_amount' => $discountAmount,
        'total' => $total,
    ]);
    return redirect()->route('invoices.create');
}

public function updateDiscount($rowId, $discountPrice)

{
    $cartItem = Cart::instance("customer{$this->activeTab}")->get($rowId);
    if ($cartItem) {
        $tax = (float) $discountPrice * ($this->taxRate / 100);
        Cart::instance("customer{$this->activeTab}")->update($rowId, [
            'price' => $discountPrice,
            'options' => [
                'tax' => $tax,
                'shelf_product_id' => $cartItem->options['shelf_product_id'],
                'conversion_factor' => $cartItem->options['conversion_factor'],
                'location_id' => $cartItem->options['location_id'],
                'row_id' => $cartItem->options['row_id'],
            ],
        ]);
        $this->cartDiscounts[$rowId] = $discountPrice;
        session()->flash('success', 'Discount updated!');
    }
}

    public function loadProducts()
    {
        if ($this->productView === 'shelf') {
            $query = ShelfProduct::where('account_id', auth()->user()->account_id)
                ->with('product')
                ->whereHas('product');
            if (!empty($this->searchProduct)) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchProduct . '%');
                });
            }
            $this->shelfProducts = $query->get();
            $this->products = $this->shelfProducts->pluck('product');
        } else {
            $query = Product::where('account_id', auth()->user()->account_id)
                ->with(['category', 'unit']);
            if (!empty($this->searchProduct)) {
                $query->where('name', 'like', '%' . $this->searchProduct . '%');
            }
            $this->products = $query->get();
            $this->shelfProducts = collect([]);
        }
    }

    
   
 
   public function render()
{
    $filteredProducts = $this->products;
    $filteredCustomers = $this->customers->filter(function ($customer) {
        return empty($this->searchCustomer) || Str::contains(Str::lower($customer->name), Str::lower($this->searchCustomer));
    })->values();

    $currentCart = Cart::instance('customer' . $this->activeTab)->content();

    return view('livewire.create-order', [
        'filteredProducts' => $filteredProducts,
        'shelfProducts' => $this->shelfProducts,
        'filteredCustomers' => $filteredCustomers,
        'currentCart' => $currentCart,
        'taxRate' => $this->taxRate,
        'cartDiscounts' => $this->cartDiscounts,
    ])->extends('layouts.tabler')->section('content');
}

    
}
