@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Echallans</h1>
    <table class="table-auto w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Vehicle Number</th>
                <th class="px-4 py-2">Mobile Number</th>
                <th class="px-4 py-2">Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($echallans as $echallan)
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2">{{ $echallan->vehicle_number }}</td>
                    <td class="px-4 py-2">{{ $echallan->mobile_number }}</td>
                    <td class="px-4 py-2">{{ $echallan->expiry_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
