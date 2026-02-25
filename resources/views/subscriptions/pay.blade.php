<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway | Rsm</title>
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('paymentportal/assets/js/script.js') }}"></script>
    <link type="text/css" rel="stylesheet" href="{{ asset('paymentportal/assets/css/style.css') }}">
</head>
<body>
<div class="container bg-light d-md-flex align-items-center">
    <!-- Subscription Details -->
    <div class="card box1 shadow-sm p-md-5 p-4">
        <div class="fw-bolder mb-4">
            <span class=""></span>
            <span class="ps-1" id="total-amount">Tsh.{{ number_format($subscription->price, 2) }}</span>
        </div>
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between text">
                <span>Plan:</span>
                <span>{{ $subscription->name }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between text mb-4">
                <span>Max Branches:</span>
                <span>{{ $subscription->max_branches ?? 'Unlimited' }}</span>
            </div>
            <div class="border-bottom mb-4"></div>
            <div class="d-flex flex-column mb-4">
                <span class="far fa-file-alt text">
                    <span class="ps-2">Plan ID:</span>
                </span>
                <span class="ps-3">{{ $subscription->id }}</span>
            </div>
            <span class="ps-2"> You're heading to be a Champion😎</span>
            <div class="border-bottom mb-4"></div>
            <span class="ps-2">Any problem reach out to us, <b>+255628172607 <br> +255744592248</b></span>
        </div>
    </div>
    <!-- Payment Options -->
    <div class="card box2 shadow-sm">
        <div class="d-flex align-items-center justify-content-between p-md-5 p-4">
            <span class="h5 fw-bold m-0">Wense Payment Gateway</span>
            <div class="btn btn-primary bar">
                <span class="fas fa-bars"></span>
            </div>
        </div>
        <ul class="nav nav-tabs mb-3 px-md-4 px-2" id="paymentTabs">
            <li class="nav-item">
                <a class="nav-link px-2 active" data-bs-toggle="tab" href="#bank-payment">Bank Payment</a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-2" data-bs-toggle="tab" href="#mobile-payment">Mobile Payment</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Bank Payment -->
            <div id="bank-payment" class="tab-pane fade show active">
                <form id="bank-payment-form" action="{{ route('subscriptions.process-payment', $subscription->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column px-md-5 px-4 mb-4">
                                <span>Credit Card</span>
                                <div class="inputWithIcon">
                                    <input class="form-control" type="text" name="card_number" placeholder="Enter credit card number" required pattern="\d{16}">
                                    <span class="">
                                        <img src="https://www.freepnglogos.com/uploads/mastercard-png/mastercard-logo-logok-15.png" alt="">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column ps-md-5 px-md-0 px-4 mb-4">
                                <span>Expiration Date</span>
                                <div class="inputWithIcon">
                                    <input type="text" class="form-control" name="expiry_date" placeholder="MM/YY" required pattern="(0[1-9]|1[0-2])\/\d{2}">
                                    <span class="fas fa-calendar-alt"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column pe-md-5 px-md-0 px-4 mb-4">
                                <span>Code CVV</span>
                                <div class="inputWithIcon">
                                    <input type="password" class="form-control" name="cvv" placeholder="Enter CVV" required pattern="\d{3,4}">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-md-5 px-4 mt-3">
                            <button type="submit" class="btn btn-primary w-100" id="bank-pay-button">Pay Tsh.{{ number_format($subscription->price, 2) }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Mobile Payment -->
            <div id="mobile-payment" class="tab-pane fade">
                <form id="mobile-payment-form" action="{{ route('subscriptions.process-payment', $subscription->id) }}" method="POST">
                    @csrf
                    <div class="d-flex flex-column px-md-5 px-4 mb-4">
                        <span>Provide your mobile number below</span>
                        <div class="inputWithIcon">
                            <input class="form-control" type="text" name="mobile_number" placeholder="Enter mobile number" required pattern="\d{10}">
                            <span class="fas fa-mobile-alt"></span>
                        </div>
                    </div>
                    <div class="col-12 px-md-5 px-4 mt-3">
                        <button type="submit" class="btn btn-primary w-100" id="mobile-pay-button">Proceed</button>
                    </div>
                    <p class="text-center mt-3 px-md-5 px-4">Click Proceed and a prompt will appear on your phone requesting you to confirm the transaction by providing your  PIN. Once completed, you will receive a confirmation SMS for this transaction.</p>
                </form>
            </div>
        </div>
    </div>
</div>
<footer class="text-center mt-4">
   <p>Copyright &copy; {{ date('Y') }} | Wense Inventory</p>
</footer>
</body>
</html>
