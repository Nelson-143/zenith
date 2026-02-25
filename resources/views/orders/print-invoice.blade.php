<!DOCTYPE html>
<html lang="en">

<head>
    <title>Print Invoice</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet"
        href="{{ asset('assets/invoice/fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
    <style>
        /* Small print styles */
        .small-print {
            font-size: 12px !important;
            padding: 10px !important;
        }

        .small-print .invoice-inner-9 {
            width: 100% !important;
            max-width: 400px !important; /* Adjust width for small print */
            margin: 0 auto !important;
        }

        .small-print .invoice-top,
        .small-print .invoice-info,
        .small-print .order-summary {
            padding: 5px !important;
        }

        .small-print .invoice-table th,
        .small-print .invoice-table td {
            padding: 5px !important;
            font-size: 12px !important;
        }

        .small-print .invoice-btn-section {
            display: none; /* Hide buttons in small print */
        }
    </style>
</head>

<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>{{ Str::title(auth()->user()->store_name) }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h1>
                                            Invoice # <span>{{ $order->invoice_no }}</span>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <div class="invoice-number">
                                        <h4 class="inv-title-1">
                                            Invoice date:
                                        </h4>
                                        <p class="invo-addr-1">
                                            {{ $order->order_date }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <h4 class="inv-title-1">Customer</h4>
                                    <p class="inv-from-1">{{ $order->customer->name }}</p>
                                    <p class="inv-from-1">{{ $order->customer->phone }}</p>
                                    <p class="inv-from-1">{{ $order->customer->email }}</p>
                                    <p class="inv-from-2">{{ $order->customer->address }}</p>
                                </div>
                                @php
                                    $user = auth()->user();
                                @endphp
                                <div class="col-sm-6 text-end mb-50">
                                    <h4 class="inv-title-1">Store</h4>
                                    <p class="inv-from-1">{{ Str::title($user->store_name) }}</p>
                                    <p class="inv-from-1">{{ $user->store_phone }}</p>
                                    <p class="inv-from-1">{{ $user->store_email }}</p>
                                    <p class="inv-from-2">{{ $user->store_address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="order-summary">
                            <div class="table-outer ">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">Item</th>
                                            <th class="align-middle text-center">Price</th>
                                            <th class="align-middle text-center">Quantity</th>
                                            <th class="align-middle text-center">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($order->details as $item)
                                            <tr>
                                                <td class="align-middle">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    Tsh.{{ ($item->unitcost) }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="align-middle text-center">
                                                {{ auth()->user()->account->currency }}{{($item->total) }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>
                                                    Subtotal
                                                </strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                {{ auth()->user()->account->currency }}{{($order->sub_total) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Tax</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                {{ auth()->user()->account->currency }}{{($order->vat) }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                {{ auth()->user()->account->currency }}{{($order->total) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Print Invoice
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download">
                            <i class="fa fa-download"></i>
                            Download Invoice
                        </a>
                        <a id="small_print_btn" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Small Print
                        </a>
                    </div>

                    {{-- back button --}}
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="{{ route('orders.index') }}" class="btn btn-lg btn-print">
                            <i class="fa fa-arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/invoice/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/html2canvas.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/app.js') }}"></script>
    <script>
        // Add event listener for the "Small Print" button
        document.getElementById('small_print_btn').addEventListener('click', function () {
            const invoiceWrapper = document.getElementById('invoice_wrapper');
            invoiceWrapper.classList.toggle('small-print');

            // Print the invoice after applying small print styles
            window.print();

            // Remove the small print class after printing
            setTimeout(() => {
                invoiceWrapper.classList.remove('small-print');
            }, 500);
        });
    </script>
</body>

</html>
