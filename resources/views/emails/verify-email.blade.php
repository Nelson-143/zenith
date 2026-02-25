@extends('layouts.email')

@section('content')
<div class="email-container">
    <div class="email-header">
        <div class="logo-container">
            <img src="{{ asset('logo.png') }}" style="width: 200px; height: auto;" alt="Wense Inventory" class="navbar-brand-image">
        </div>
    </div>
    <div class="email-body">
        <h1>Welcome to Zenith</h1>
        <p>You're about to experience stock management like never before. zenith combines powerful analytics, intuitive design, and cutting-edge technology to transform how you manage investments.</p>

        <a href="{{ $verificationUrl }}" class="btn">Activate Your Account</a>

        <p style="font-size: 14px; color: #64748b;">Please verify your email within 24 hours to complete your registration.</p>

        <p>Thank you for choosing zenith. We are excited to have you on board!</p>
    </div>
    <div class="email-footer">
        <p>If you did not request this account, please disregard this email.</p>
        <div class="separator"></div>

        <p>&copy; {{ date('Y') }} Zenith. All rights reserved.</p>
        <div class="contact-info">
            <p>zenith | Tanzania, Dar es Salaam | <a href="mailto:support@zenith.com" style="color: #1a3c6e; text-decoration: none;">support@zenith.com</a></p>
            <p><a href="#" style="color: #1a3c6e; text-decoration: none;">Privacy Policy</a> | <a href="#" style="color: #1a3c6e; text-decoration: none;">Terms of Service</a> | <a href="https://romansofts.co.tz" style="color: #1a3c6e; text-decoration: none;">rs</a></p>
        </div>
    </div>
</div>
@endsection
