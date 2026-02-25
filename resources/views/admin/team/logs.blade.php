@extends('layouts.tabler')

@section('title')
    {{ __('Team Logs') }}
@endsection
@section('me')
    @parent
@endsection

@section('Damage')
<div class="container">
    <h2>{{ __('Activity Logs for your Team') }}</h2>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('User') }}</th>
                <th>{{ __('Activity') }}</th>
                <th>{{ __('Details') }}</th>  
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->created_at }}</td>
                    <td>{{ $activity->causer->name }}</td>
                    <td>{{ $activity->description }}</td>
                    <td>
                        @if($activity->properties->isNotEmpty())
                            {{ json_encode($activity->properties) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $activities->links() }}
</div>
@endsection