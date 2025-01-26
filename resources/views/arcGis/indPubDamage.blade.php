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
</div>
@endsection
