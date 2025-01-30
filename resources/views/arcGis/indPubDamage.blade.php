@extends('admin.admin_layout')

@section('content')
<nav aria-label="breadcrumb" class="mx-4 my-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        {{-- <li class="breadcrumb-item"><i class="fas fa-angle-right"></i></li> --}}
        <li class="breadcrumb-item active" aria-current="page"><strong>Ind & Pub Damage</strong></li>
    </ol>
</nav>
<div class="container">
    <h1>Disaster Impact Data</h1>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>District</th>
                <th>DSD</th>
                <th>Description</th>
                <th>Coordinates</th>
                <th>DS Approved</th>
                <th>District Approved</th>
                <th>National Approved</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mergedData as $item)
                <tr>
                    <td>{{ $item['attributes']['objectid'] ?? 'N/A' }}</td>
                    <td>{{ $item['attributes']['DISTRICT_N'] ?? 'N/A' }}</td>
                    <td>{{ $item['attributes']['DSD_N'] ?? 'N/A' }}</td>
                    <td>{{ $item['attributes']['disas_event'] ?? 'N/A' }}</td>
                    <td>
                        {{ $item['geometry']['x'] ?? 'N/A' }}, 
                        {{ $item['geometry']['y'] ?? 'N/A' }}
                    </td>
                    <td>{{ $item['ds_approved'] ? 'Yes' : 'No' }}</td>
                    <td>{{ $item['district_approved'] ? 'Yes' : 'No' }}</td>
                    <td>{{ $item['national_approved'] ? 'Yes' : 'No' }}</td>
                    <td>
                    <form action="{{ route('update.approval', ['objectid' => $item['attributes']['objectid']]) }}" method="POST">
                        @csrf
                        <button type="submit">Approve</button>
                    </form>
                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No data available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4" id="map"></div>
    
</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize the map
                    var map = L.map('map').setView([7.8731, 80.7718], 7);
        
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 18
                    }).addTo(map);
        
                    // Add markers for each location
                    var locations = @json($mapData);
                    var markers = [];
                    
                    locations.forEach(function(location) {
                        // Parse coordinates as floats and check if they're valid numbers
                        var lat = parseFloat(location.y);
                        var lng = parseFloat(location.x);
                        
                        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                            console.log(`Adding marker at: ${lat}, ${lng}`); // Debug log
                            
                            try {
                                var marker = L.marker([lat, lng]).addTo(map);
                                marker.bindPopup(`
                                    <strong>District:</strong> ${location.district}<br>
                                    <strong>DSD:</strong> ${location.dsd}<br>
                                    <strong>Description:</strong> ${location.description}<br>
                                    <strong>Coordinates:</strong> ${lng}, ${lat}
                                `);
                                markers.push(marker);
                            } catch (e) {
                                console.error('Error adding marker:', e, location);
                            }
                        } else {
                            console.warn('Invalid coordinates:', location);
                        }
                    });
        
                    // If there are markers, fit the map to show all of them
                    if (markers.length > 0) {
                        var group = new L.featureGroup(markers);
                        map.fitBounds(group.getBounds().pad(0.1)); // Add 10% padding around the bounds
                    }
                });
            </script>
            
@endsection
