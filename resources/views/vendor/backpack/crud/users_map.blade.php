@extends(backpack_view('blank'))

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>User Locations</h5>
            </div>
            <div class="card-body">
                <div id="usersMap" style="height: 500px;"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Top Locations</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    @foreach($topLocations as $location)
                    <tr>
                        <td>{{ $location->city }}</td>
                        <td>{{ $location->country }}</td>
                        <td class="text-end">{{ $location->user_count }} users</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

@push('after_scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Initialize map
    const map = L.map('usersMap').setView([20, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    
    // Add user markers
    @foreach($usersWithGeo as $user)
        @if($user->latitude && $user->longitude)
            L.marker([{{ $user->latitude }}, {{ $user->longitude }}])
                .bindPopup(`
                    <b>{{ $user->name }}</b><br>
                    {{ $user->store_name }}<br>
                    {{ $user->store_address }}
                `)
                .addTo(map);
        @endif
    @endforeach
</script>
@endpush

@push('after_styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    #usersMap { 
        border-radius: 5px;
        border: 1px solid #eee;
    }
</style>
@endpush
@endsection