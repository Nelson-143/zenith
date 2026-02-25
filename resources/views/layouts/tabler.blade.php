<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2196f3">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="description" content="Zenith is a powerful inventory management system designed to streamline your operations, enhance visibility, and optimize stock control. Start your journey with Zenith and elevate your inventory management for free!"/>
<meta name="canonical" content="https://zenith.rs/demo/sign-in.html">
<meta name="twitter:image:src" content="https://zenith.rs/static/og.png">
<meta name="twitter:site" content="@zenith_pro">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Zenith: Premium Inventory Management System with Responsive and High-Quality UI.">
<meta name="twitter:description" content="Zenith is a comprehensive inventory management solution that comes with a range of features to help you manage your stock efficiently. Start your adventure with Zenith and transform your inventory management for free!">
<meta property="og:description" content="Zenith is a powerful inventory management system designed to streamline your operations, enhance visibility, and optimize stock control. Start your journey with Zenith and elevate your inventory management for free!">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>



                                                   /* Loader-specific CSS */
                                                    .page-loader {
                                                        display: flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                        position: fixed;
                                                        top: 0;
                                                        left: 0;
                                                        width: 100%;
                                                        height: 100%;
                                                        background-color: white;
                                                        z-index: 9999;
                                                    }
                                                    .hidden {
                                                        display: none;
                                                    }
                                                </style>
                                                <!-- Page Loader -->
                                                <div id="page-loader" class="page-loader">
                                                <div class="text-center">
                                                    <div class="mb-3">

                                                    </div>
                                                    <div class="text-secondary mb-3">making it for you  .....</div>

                                                    </div>
                                                </div>
                                                </div>
                                                <!-- Scripts -->

                                                <script>
    document.addEventListener("DOMContentLoaded", function () {
        const loader = document.getElementById("page-loader");
        const content = document.getElementById("main-content");

        // Simulate loading delay
        setTimeout(() => {
            loader.classList.add("hidden"); // Hide loader
            content.classList.remove("hidden"); // Show main content

            // Reinitialize Tabler components
            if (window.Tabler) {
                Tabler.init();
            }
        }, 100); // Adjust delay as needed
    });
</script>



    <title>@yield('title')</title>

    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('logo.png') }}" rel="icon" />


    <!--Lord icons ---->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <style>
        /* Default color for light mode */
        lord-icon {
            filter: invert(0); /* Black in light mode */
            vertical-align: middle; /* Align icon with text */
            margin-right: 8px; /* Space between icon and text */
        }

        /* Color for dark mode */
        @media (prefers-color-scheme: dark) {
        lord-icon {
            filter: invert(0); /* Black in dark mode */
        }
    }
    </style>



    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        .form-control:focus {
            box-shadow: none;
        }
    </style>

    {{-- - Page Styles - --}}
    @stack('page-styles')
    @livewireStyles
</head>
<!-- for email verification -->
@if (auth()->check() && !auth()->user()->hasVerifiedEmail())
    <div class="alert alert-warning">
        <p>
            Please verify your email address. We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
            <a href="{{ route('verification.resend') }}">Resend Verification Email</a>
        </p>
    </div>
@endif
<!--the xxx-->
<body>
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>

    <div class="page">
        <header class="navbar navbar-expand-md d-print-none ">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                    aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="{{ url('/') }}">
    <img src="{{ asset('logo.png') }}" alt="Mauzo Pro" class="navbar-brand-image" style="width: 50px; height: 50px; object-fit: cover;">
</a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="d-none d-md-flex">

                            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
                               data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                            </a>
                            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
                               data-bs-placement="bottom">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                            </a>
                    </div>
                    <!-- the notification-panel -->
                    <x-notification-panel />

                                            <div class="nav-item dropdown">
                                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-rounded shadow-none" style="background-color: gold; color: black; border-radius: 50%; width: 40px; height: 40px; display: inline-block; text-align: center; line-height: 40px;">{{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->name, strpos(Auth::user()->name, ' ') + 1, 1) }}</span>
                            <div class="d-none d-xl-block ps-2">
                            {{ Auth::user()->name ?? '' }}
                            <div class="mt-1 small text-muted">{{ Auth::user()->getRoleNames()->first() }}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon dropdown-item-icon icon-tabler icon-tabler-settings" width="24"
                                    height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                    </path>
                                    <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                </svg>
                                {{ __('Account') }}
                            </a>
                            @role('Super Admin')
                            <a href="{{ route('subscriptions.index') }}" class="dropdown-item">
                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart-up"><path stroke="none" d="M0 0h24v24H0z"
                            fill="none"stroke-linecap="round" stroke-linejoin="round"/><path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                            <path d="M12.5 17h-6.5v-14h-2" /><path d="M6 5l14 1l-.854 5.977m-2.646 1.023h-10.5" />
                            <path d="M19 22v-6" /><path d="M22 19l-3 -3l-3 3" /></svg>
                                 {{ __('Subscriptions') }}
                            </a>
                            @endrole
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon dropdown-item-icon icon-tabler icon-tabler-logout" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                                        <path d="M9 12h12l-3 -3" />
                                        <path d="M18 15l3 -3" />
                                    </svg>
                                    {{ __('Logout') }}
                                </button>

                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </header>

        <header class="navbar-expand-md">
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="navbar">
                    <div class="container-xl">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->is('dashboard*') ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Dashboard') }}
                                    </span>
                                </a>
                            </li>

                            <li class="nav-item dropdown {{ request()->is('products*','shelf-products*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-packages" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                                            <path d="M2 13.5v5.5l5 3" />
                                            <path d="M7 16.545l5 -3.03" />
                                            <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                                            <path d="M12 19l5 3" />
                                            <path d="M17 16.5l5 -3" />
                                            <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" />
                                            <path d="M7 5.03v5.455" />
                                            <path d="M12 8l5 -3" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Products') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('products.index') }}">
                                                {{ __('Products') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('shelf-products.index') }}">
                                                {{ __('Shelf-Products') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('location-setup') }}">
                                                {{ __('Products Locations') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>


                            <li class="nav-item dropdown {{ request()->is('orders*','pos*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-package-export" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12v9" />
                                            <path d="M12 12l-8 -4.5" />
                                            <path d="M15 18h7" />
                                            <path d="M19 15l3 3l-3 3" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Orders') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('orders.index') }}">
                                                {{ __('All') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('orders.complete') }}">
                                                {{ __('Completed') }}
                                            </a>
                                            <a class="dropdown-item" href="{{ route('orders.pending') }}">
                                                {{ __('Pending') }}
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </li>

                            @role('Super Admin')
                            <li class="nav-item dropdown {{ request()->is('purchases*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-package-import" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" />
                                            <path d="M12 12l8 -4.5" />
                                            <path d="M12 12v9" />
                                            <path d="M12 12l-8 -4.5" />
                                            <path d="M22 18h-7" />
                                            <path d="M18 15l-3 3l3 3" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Purchases') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('purchases.index') }}">
                                                {{ __('All') }}
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('purchases.approvedPurchases') }}">
                                                {{ __('Approval') }}
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('purchases.purchaseReport') }}">
                                                {{ __('Daily Purchase Report') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endrole

                            <li
                                class="nav-item dropdown {{ request()->is('suppliers*', 'customers*','debts*','expenses*','stock*','budgets*','gamification*','quotations*','ads*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-layers-subtract" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M8 4m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" />
                                            <path d="M16 16v2a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-8a2 2 0 0 1 2 -2h2" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Pages') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">

                                        <a class="dropdown-item" href="{{ route('quotations.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/rguiapej.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Quotations') }}
                                                     </a>
                                                     @role('Super Admin')
                                            <a class="dropdown-item" href="{{ route('suppliers.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/pbrgppbb.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Suppliers') }}
                                                     </a>
                                                     @endrole
                                            <a class="dropdown-item" href="{{ route('customers.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/iazmohzf.json"
                                                        trigger="hover"
                                                            colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Customers') }}
                                                     </a>
                                            <a class="dropdown-item" href="{{ route('debts.index') }}">
                                            <lord-icon
                                                src="https://cdn.lordicon.com/xuyycdjx.json"
                                                trigger="morph"
                                                colors="primary:black"
                                                state="morph-card"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Debts') }}
                                                     </a>

                                                    <a class="dropdown-item" href="{{ route('expenses.index') }}">
                                                    <lord-icon
                                                      src="https://cdn.lordicon.com/gjjvytyq.json"
                                                    trigger="hover"
                                                    colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Expences') }}
                                                     </a>
                                                     @role('Super Admin')
                                                     <a class="dropdown-item" href="{{ route('budgets.index') }}">
                                                    <lord-icon
                                                    src="https://cdn.lordicon.com/ncitidvz.json"
                                                    trigger="hover"
                                                    colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Budgets') }}
                                                     </a>
                                                    @endrole

                                                   <a class="dropdown-item" href="{{ route('stock.transfer') }}">
                                                  <lord-icon
                                                       src="https://cdn.lordicon.com/qnpnzlkk.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Transfer/Damage') }}
                                                     </a>

                                                     <a class="dropdown-item" href="{{ route('ads.generator') }}">
                                            <lord-icon
                                            src="https://cdn.lordicon.com/wsaaegar.json"
                                                trigger="hover"
                                                stroke="bold"
                                                colors="primary:#000000,secondary:#000000"
                                            colors="primary:black"
                                                          style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Ads Generator') }}
                                                     </a>
                                                     @role('Super Admin')
                                                     <a class="dropdown-item" href="{{ route('gamification.board') }}">
                                            <lord-icon
                                            src="https://cdn.lordicon.com/jyjslctx.json"
                                            trigger="morph"
                                            state="morph-pie-chart"
                                            colors="primary:black"
                                                          style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('RsmPlay') }}
                                                     </a>
                                                     @endrole
                                        </div>
                                    </div>
                                </div>
                            </li>


                    @role('Super Admin')
                    <li class="nav-item {{ request()->is('reports*') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('reports.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-pie">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M10 3.2a9 9 0 1 0 10.8 10.8a1 1 0 0 0 -1 -1h-6.8a2 2 0 0 1 -2 -2v-7a.9 .9 0 0 0 -1 -.8" />
                                    <path d="M15 3.5a9 9 0 0 1 5.5 5.5h-4.5a1 1 0 0 1 -1 -1v-4.5" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Reports') }}
                            </span>
                        </a>
                    </li>
                     <!----FinAssist---->
                    <li class="nav-item {{ request()->is('finassist*') ? 'active' : null }}">
                                <a class="nav-link" href="{{ route('finassist') }}">
                                    <span
                                        class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"
                                        fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"
                                         stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-bolt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 3l0 7l6 0l-8 11l0 -7l-6 0l8 -11" /></svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('MauzoAssist') }}
                                    </span>
                                </a>
                            </li>
                    @endrole
                        <!---settings--->
                            <li
                                class="nav-item dropdown {{ request()->is('users*', 'categories*', 'units*' , 'team*','branches*') ? 'active' : null }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon icon-tabler icon-tabler-settings" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">
                                        {{ __('Settings') }}
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
{{--                                            --}}{{-- <a class="dropdown-item" href="{{ route('users.index') }}">--}}
{{--                                                    {{ __('Users') }}--}}
{{--                                                </a> --}}
                                            <a class="dropdown-item" href="{{ route('categories.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/jnikqyih.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Categories') }}
                                                     </a>
                                            <a class="dropdown-item" href="{{ route('units.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/jgnvfzqg.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Units') }}
                                                     </a>
                                                     @role('Super Admin')
                                            <a class="dropdown-item" href="{{ route('admin.team.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/hrjifpbq.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Your Team') }}
                                                     </a>


                                                     <a class="dropdown-item" href="{{ route('branches.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/mjcariee.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Set Branch') }}
                                                     </a>
                                                     @endrole
                                                     <a class="dropdown-item" href="{{ route('locations.index') }}">
                                            <lord-icon
                                                      src="https://cdn.lordicon.com/bpmglzll.json"
                                                        trigger="hover"
                                                        colors="primary:black"
                                                      style="width:20px;height:20px">
                                                         </lord-icon>
                                                          {{ __('Set Location') }}
                                                     </a>
                                        </div>

                                    </div>
                                </div>
                            </li>

                       <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
    <form action="" method="get" autocomplete="off" novalidate>
        <div class="input-icon">
            <span class="input-icon-addon">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                    <path d="M21 21l-6 -6" />
                </svg>
            </span>
            <input type="text" name="search" id="search" value="" class="form-control" placeholder="{{ __('Search…') }}" aria-label="Search in website">
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#search').on('keyup', function() {
            var search = $(this).val().toLowerCase();
            $('.page-wrapper *').each(function() {
                if ($(this).text().toLowerCase().indexOf(search) !== -1) {
                    $(this).addClass('highlight');
                } else {
                    $(this).removeClass('highlight');
                }
            });
        });
    });
</script>

<style>
    .highlight {
        background-color: yellow;
    }

</style>

<li class="nav-item dropdown {{ request()->is('') ? 'active' : null }}">
    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
        <span class="nav-link-icon d-md-none d-lg-inline-block">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-language-hiragana">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 5h7" />
                <path d="M7 4c0 4.846 0 7 .5 8" />
                <path d="M10 8.5c0 2.286 -2 4.5 -3.5 4.5s-2.5 -1.135 -2.5 -2c0 -2 1 -3 3 -3s5 .57 5 2.857c0 1.524 -.667 2.571 -2 3.143" />
                <path d="M12 20l4 -9l4 9" />
                <path d="M19.1 18h-6.2" />
            </svg>
        </span>
        <span class="nav-link-title">
            {{ __('Language') }}
        </span>
    </a>
    <div class="dropdown-menu">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                <a class="dropdown-item" href="{{ route('change-locale', 'en') }}">
                    {{ __('English') }}
                </a>
                <a class="dropdown-item" href="{{ route('change-locale', 'sw') }}">
                    {{ __('Swahili') }}
                </a>
            </div>
        </div>
    </div>
</li>


                        </ul>

                    </div>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <div>
                @yield('content')
                @yield('finassist')
                @yield('Debts')
                @yield('rsmplay')
                @yield('stocktrans')
                @yield('Damage')
                @yield('matumizi')
                @yield('budget')
                @yield('report')
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <!-- <li class="list-inline-item"><a href="https://tabler.io/docs" target="_blank"
                                        class="link-secondary" rel="noopener">Documentation</a></li> -->
                                <!-- <li class="list-inline-item"><a href="" class="link-secondary">License</a> -->
                                <!-- </li> -->
                                <!-- <li class="list-inline-item"><a href="https://github.com/tabler/tabler"
                                        target="_blank" class="link-secondary" rel="noopener">Source code</a></li> -->
                                <!-- <li class="list-inline-item"> -->
                                    <!-- <a href="https://github.com/sponsors/codecalm" target="_blank" -->
                                        <!-- class="link-secondary" rel="noopener"> -->
                                        <!-- Download SVG icon from http://tabler-icons.io/i/heart -->
                                        <!-- <svg xmlns="http://www.w3.org/2000/svg"
                                            class="icon text-pink icon-filled icon-inline" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                                        </svg> -->
                                        <!-- Sponsor -->
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; {{ now()->year }}
                                    <a href="." class="link-secondary">zenith</a>.rs
                                </li>
                                <!-- <li class="list-inline-item"> -->
                                    <!-- <a href="./changelog.html" class="link-secondary" rel="noopener">
                                        v1.0.0
                                    </a> -->
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
<!--page loader starts --->
<script>
    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            }
        });
    }

    function markNotificationAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            }
        });
    }



</script>


                                            <!--end of loader snip---
    <!-- Libs JS -->
    @stack('page-libraries')
    <!-- Tabler Core -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('dist/js/demo.min.js') }}" defer></script>
    {{-- - Page Scripts - --}}
    @stack('page-scripts')

    @livewireScripts
    <script>
 if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('{{ asset('service-worker.js') }}')
      .then((registration) => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch((error) => {
        console.error('Service Worker registration failed:', error);
      });
  }
</script>
<script>
  let deferredPrompt;

  window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    deferredPrompt = event;

    // Show the install button
    const installButton = document.getElementById('install-button');
    if (installButton) {
      installButton.style.display = 'block';
    }
  });

  document.getElementById('install-button').addEventListener('click', () => {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then((choiceResult) => {
        if (choiceResult.outcome === 'accepted') {
          console.log('User accepted the install prompt');
        } else {
          console.log('User dismissed the install prompt');
        }
        deferredPrompt = null;
      });
    }
  });

  async function translatePage(targetLanguage) {
    const translatableElements = document.querySelectorAll('[data-translate]');

    for (const element of translatableElements) {
        const text = element.innerText;
        const sourceLanguage = 'en'; // Default source language
        const response = await fetch('/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                text: text,
                sourceLanguage: sourceLanguage,
                targetLanguage: targetLanguage,
            }),
        });

        const data = await response.json();
        element.innerText = data.translatedText;
    }
}

// Example: Translate the page to Swahili when a button is clicked
document.getElementById('translate-to-swahili').addEventListener('click', () => {
    translatePage('sw');
});
</script>

</body>
</html>
