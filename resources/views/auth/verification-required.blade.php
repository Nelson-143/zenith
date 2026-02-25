@extends('layouts.tabler')

@section('title', 'Verification Required')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">Verification Required</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h2>Please Verify Your Email Address</h2>
                <p class="text-muted">
                    We've sent a verification link to your email address. Please check your inbox and click the link to verify your email.
                </p>
                <p class="text-muted">
                    If you didn't receive the email, <a href="{{ route('verification.resend') }}">click here to resend it</a>.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection