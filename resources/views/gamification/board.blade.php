@extends('layouts.tabler')

@section('title')
    RsmPlay⚡
@endsection

@section('me')
    @parent
@endsection

@section('rsmplay')

<div class="page page-center">
      <div class="container-tight py-4">
        <div class="empty">
          <div class="empty-img"><img src="https://tabler.io/_next/image?url=%2Fillustrations%2Fdark%2Fcomputer-fix.png&w=800&q=75"/>
          </div>
          <p class="empty-title">Hold Tight, the Best is in Sight 🚀</p>
          <p class="empty-subtitle text-secondary">
            This site is Still in Production mode
          <div class="empty-action">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
              <!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
              Take me home
            </a>
          </div>
        </div>
      </div>
    </div>
@endsection