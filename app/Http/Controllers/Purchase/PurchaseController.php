<?php

namespace app\Http\Controllers\Purchase;

use app\Enums\PurchaseStatus;
use app\Http\Controllers\Controller;
use app\Http\Requests\Purchase\StorePurchaseRequest;
use app\Models\Category;
use app\Models\Product;
use app\Models\Purchase;
use app\Models\PurchaseDetails;
use app\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Ensures that the user is authenticated
    }

    /**
     * Display a listing of the purchases.
     */
    public function index()
    {
        // Fetch purchases of the logged-in user with related supplier details
        $purchases = Purchase::with('supplier')
            ->where('user_id', auth()->id())
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->get();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Display a listing of approved purchases.
     */
    public function approvedPurchases()
    {
        $purchases = Purchase::with('supplier') // Eager load the supplier
            ->where('user_id', auth()->id())
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->where('status', 1) // Assuming status 1 means approved
            ->get();

        return view('purchases.approved', compact('purchases'));
    }

    /**
     * Show the specified purchase.
     */
    public function show($uuid)
    {
        // Eager load supplier and details for the specific purchase
        $purchase = Purchase::with(['supplier', 'details.product'])
            ->where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->firstOrFail();
    
        return view('purchases.show', compact('purchase'));
    }
    

    /**
     * Show the form for editing the specified purchase.
     */
    public function edit($uuid)
    {
        $purchase = Purchase::with('supplier')
            ->where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->firstOrFail();

        $suppliers = Supplier::all();
        $products = $purchase->details; // Fetch associated purchase details (products)

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    /**
     * Show the form for creating a new purchase.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created purchase in the database.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validated = $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'reference' => 'required|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unitcost' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
        ]);
    
        // Generate purchase number automatically
        $purchaseNumber = $this->generatePurchaseNumber();
    
        // Initialize total amount
        $totalAmount = 0;
    
        // Calculate the total amount based on products
        foreach ($validated['products'] as $product) {
            $totalAmount += $product['quantity'] * $product['unitcost'];
        }
    
        // Calculate the total tax amount based on the total price
        $taxAmount = $totalAmount * ($validated['tax_rate'] / 100);
        $totalAmountWithTaxes = $totalAmount + $taxAmount;
    
        // Create the purchase
        $purchase = Purchase::create([
            'supplier_id' => $validated['supplier_id'],
            'date' => $validated['date'],
            'total_amount' => $totalAmountWithTaxes,
            'taxes' => $taxAmount,
            'created_by' => auth()->id(),
            'purchase_no' => $purchaseNumber,
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'account_id' => auth()->user()->account_id,
        ]);
    
        // Save the purchase details and update product quantities
        foreach ($validated['products'] as $product) {
            $productModel = Product::findOrFail($product['id']);
            $previousStock = $productModel->quantity; // Get previous stock before updating
            $currentStock = $previousStock + $product['quantity']; // Calculate current stock
    
            // Update the product stock
            $productModel->update(['quantity' => $currentStock]);
    
            // Create purchase details
            PurchaseDetails::create([
                'purchase_id' => $purchase->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'unitcost' => $product['unitcost'],
                'total' => $product['quantity'] * $product['unitcost'],
                'account_id' => auth()->user()->account_id,
                'previous_stock' => $previousStock,
                'current_stock' => $currentStock,
            ]);
        }
    
        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully!');
    }

    // Placeholder function for generating purchase numbers
    private function generatePurchaseNumber()
    {
        // Implement your logic for generating a purchase number
        return 'PUR-' . time(); // Example: Generates a unique purchase number
    }

    public function update(StorePurchaseRequest $request, $uuid)
    {
        $purchase = Purchase::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id)
            ->firstOrFail();
    
        // Start a database transaction
        DB::beginTransaction();
        try {
            // Initialize total amount
            $totalAmount = 0;
    
            // Only proceed if products exist in the request
            if ($request->has('products') && is_array($request->products)) {
                // Delete previous purchase details
                foreach ($purchase->details as $detail) {
                    // Update product quantities back to previous stock
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $product->quantity -= $detail->quantity; // Reduce quantity
                        $product->save(); // Save updated quantity
                    }
                }
    
                // Now delete previous purchase details
                $purchase->details()->delete();
    
                // Loop through new products to add and calculate total
                foreach ($request->products as $product) {
                    if (isset($product['id'], $product['quantity'], $product['unitcost'])) {
                        $productTotal = $product['quantity'] * $product['unitcost'];
                        $totalAmount += $productTotal;
    
                        // Add the new product details
                        PurchaseDetails::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $product['id'],
                            'quantity' => $product['quantity'],
                            'unitcost' => $product['unitcost'],
                            'total' => $productTotal,
                        ]);
    
                        // Update product quantities
                        $productModel = Product::findOrFail($product['id']);
                        $previousStock = $productModel->quantity; // Get previous stock
                        $currentStock = $previousStock + $product['quantity']; // Calculate new stock
    
                        // Update the product stock
                        $productModel->update(['quantity' => $currentStock]);
                    }
                }
            }
    
            // Update purchase details, including recalculated total amount
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_amount' => $totalAmount > 0 ? $totalAmount : $purchase->total_amount,
                'updated_by' => auth()->id(),
            ]);
    
            // Commit the transaction
            DB::commit();
    
            return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
        } catch (Exception $e) {
            // Roll back the transaction in case of an error
            DB::rollBack();
    
            return back()->withErrors(['error' => 'Error updating purchase: ' . $e->getMessage()]);
        }
    }

    public function approve($uuid)
    {
        // Find the purchase by UUID
        $purchase = Purchase::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->firstOrFail();

        // Update the status to approved (assuming 1 represents 'approved')
        $purchase->status = 1;
        $purchase->save();

        // Redirect back with a success message
        return redirect()->route('purchases.index')->with('success', 'Purchase approved successfully.');
    }

    /**
     * Remove the specified purchase from the database.
     */
    public function destroy($uuid)
    {
        $purchase = Purchase::where('uuid', $uuid)
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->firstOrFail();
    
        // Loop through purchase details to update product quantities
        foreach ($purchase->details as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                // Reduce the product quantity by the quantity in the purchase details
                $product->quantity -= $detail->quantity;
                $product->save(); // Save the updated product quantity
            }
        }
    
        // Now delete the purchase
        $purchase->delete();
    
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
    /**
     * Display the purchase report view.
     */
    public function purchaseReport()
    {
        return view('purchases.report');
    }

    /**
     * Generate the purchase report based on user input (date filters, etc.).
     */
    public function getPurchaseReport(Request $request)
    {
        $purchases = Purchase::where('user_id', auth()->id())
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->when($request->from_date, function ($query) use ($request) {
                return $query->whereDate('date', '>=', Carbon::parse($request->from_date));
            })
            ->when($request->to_date, function ($query) use ($request) {
                return $query->whereDate('date', '<=', Carbon::parse($request->to_date));
            })
            ->with(['supplier', 'products']) // Eager load suppliers and products
            ->get();

        return view('purchases.report', compact('purchases'));
    }

    /**
     * Export the purchase report to Excel using joins.
     */
    public function exportPurchaseReport(Request $request)
    {
        // Fetch purchases with joined product details
        $purchases = DB::table('purchases')
            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
            ->join('purchase_details', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->join('products', 'purchase_details.product_id', '=', 'products.id')
            ->select(
                'purchases.purchase_no',
                'suppliers.name as supplier_name',
                'purchases.date',
                'purchases.total_amount',
                'purchases.status',
                'products.name as product_name',
                'products.code as product_code',
                'purchase_details.quantity',
                'purchase_details.unitcost as unit_cost', // Use unitcost from purchase_details
                DB::raw("CASE WHEN purchases.created_by = 1 THEN 'Admin' ELSE 'User ' END as created_by") // Conditional logic
            )
            ->where('purchases.user_id', auth()->id())
            ->where('purchases.account_id', auth()->user()->account_id) // Filter by account_id
            ->get();

        return $this->exportExcel($purchases);
    }

    public function exportExcel($purchases)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the column headings
        $sheet->setCellValue('A1', 'Purchase No');
        $sheet->setCellValue('B1', 'Supplier');
        $sheet->setCellValue('C1', 'Date');
        $sheet->setCellValue('D1', 'Total Amount');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Product');
        $sheet->setCellValue('G1', 'Product Code');
        $sheet->setCellValue('H1', 'Quantity');
        $sheet->setCellValue('I1', 'Unit Cost');
        $sheet->setCellValue('J1', 'Created By');

        // Fill data
        $row = 2;
        foreach ($purchases as $purchase) {
            $sheet->setCellValue("A{$row}", $purchase->purchase_no);
            $sheet->setCellValue("B{$row}", $purchase->supplier_name);
            $sheet->setCellValue("C{$row}", $purchase->date);
            $sheet->setCellValue("D{$row}", $purchase->total_amount);
            $sheet->setCellValue("E{$row}", $purchase->status ? 'Completed' : 'Pending');
            $sheet->setCellValue("F{$row}", $purchase->product_name);
            $sheet->setCellValue("G{$row}", $purchase->product_code);
            $sheet->setCellValue("H{$row}", $purchase->quantity);
            $sheet->setCellValue("I{$row}", $purchase->unit_cost);
            $sheet->setCellValue("J{$row}", $purchase->created_by);
            $row++;
        }

        // Save as Excel
        $writer = new Xls($spreadsheet);
        $filename = "purchases_report_" . now()->format('Y-m-d') . ".xls";

        // Return the file as a download
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }

    // For pie chart in dashboard
    public function getPurchasesBySupplier()
    {
        $userId = auth()->id(); // Get the logged-in user ID

        // Fetch total purchase amounts grouped by supplier
        $data = Purchase::select('supplier_id', DB::raw('SUM(total_amount) as total_amount'))
            ->where('user_id', $userId)
            ->where('account_id', auth()->user()->account_id) // Filter by account_id
            ->groupBy('supplier_id')
            ->with('supplier:name,id') // Eager load supplier name
            ->get();

               // Format the data for the pie chart
               $chartData = $data->map(function ($purchase) {
                return [
                    'label' => $purchase->supplier->name,
                    'value' => $purchase->total_amount,
                ];
            });
    
            return response()->json($chartData); // Return data in JSON format
        }
    }