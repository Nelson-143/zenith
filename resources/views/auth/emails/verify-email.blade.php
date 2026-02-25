@extends('layouts.auth')

@section('content')
<div class="text-center">
    <div class="my-5">
        <p class="fs-h3 text-secondary">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>
</div>

{{-- Display success or error messages --}}
@if (session('status') == 'verification-link-sent')
    <div class="alert alert-success" role="alert">
        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        {{ $errors->first() }}
    </div>
@endif

{{-- Resend verification email --}}
<form action="{{ route('verification.send') }}" method="POST" autocomplete="off">
    @csrf
    <button type="submit" class="btn btn-primary w-100">
        {{ __('Resend Verification Email') }}
    </button>
</form>

{{-- Log out button --}}
<form action="{{ route('logout') }}" method="POST" autocomplete="off" class="mt-4">
    @csrf
    <button type="submit" class="btn btn-secondary w-100">
        {{ __('Log Out') }}
    </button>
</form>
@endsection
