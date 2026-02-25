<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Location;
use App\Models\ProductLocation;
use App\Models\ProductTransferLog;
use App\Models\DamagedProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    public function transfer()
    {
        $accountId = auth()->user()->account_id;
        $products = Product::where('account_id', $accountId)->get();
        $locations = Location::where('account_id', $accountId)->get();
        $stockTransfers = ProductTransferLog::where('account_id', $accountId)->with('product', 'fromLocation', 'toLocation')->get();

        Log::info('Fetched Products: ', $products->toArray());
        Log::info('Fetched Stock Transfers: ', $stockTransfers->toArray());

        return view('stock.transfer', compact('products', 'locations', 'stockTransfers'));
    }

    public function transferStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_location_id' => 'required|exists:locations,id',
            'to_location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $accountId = auth()->user()->account_id;
        $data = $request->all();
        $data['account_id'] = $accountId;

        $fromProductLocation = ProductLocation::where('product_id', $data['product_id'])
            ->where('location_id', $data['from_location_id'])
            ->where('account_id', $accountId)
            ->firstOrFail();

        if ($fromProductLocation->quantity < $data['quantity']) {
            return redirect()->back()->withErrors(['quantity' => 'Insufficient quantity in the from location.']);
        }

        $fromProductLocation->quantity -= $data['quantity'];
        $fromProductLocation->save();

        $toProductLocation = ProductLocation::firstOrNew([
            'product_id' => $data['product_id'],
            'location_id' => $data['to_location_id'],
            'account_id' => $accountId,
        ]);
        $toProductLocation->quantity += $data['quantity'];
        $toProductLocation->save();

        ProductTransferLog::create($data);

        return redirect()->back()->with('success', 'Stock transfer recorded successfully.');
    }

    public function showDamagedForm()
    {
        $accountId = auth()->user()->account_id;
        $products = Product::where('account_id', $accountId)->get();

        foreach ($products as $product) {
            $totalDamagedQuantity = DamagedProduct::where('product_id', $product->id)
                ->where('account_id', $accountId)
                ->sum('quantity');
            $product->remaining_quantity = $product->productLocations()->sum('quantity') - $totalDamagedQuantity;
        }

        $damagedProducts = DamagedProduct::where('account_id', $accountId)->with('product')->get();

        return view('stock.damaged', compact('products', 'damagedProducts'));
    }

    public function recordDamaged(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'location_id' => 'required|exists:locations,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        $accountId = auth()->user()->account_id;
        $data = $request->all();
        $data['account_id'] = $accountId;

        $productLocation = ProductLocation::where('product_id', $data['product_id'])
            ->where('location_id', $data['location_id'])
            ->where('account_id', $accountId)
            ->firstOrFail();

        if ($productLocation->quantity < $data['quantity']) {
            return redirect()->back()->withErrors(['quantity' => 'Not enough quantity in the selected location to record this damage.']);
        }

        $productLocation->quantity -= $data['quantity'];
        $productLocation->save();

        DamagedProduct::create($data);

        return redirect()->back()->with('success', 'Damaged product recorded successfully.');
    }
}