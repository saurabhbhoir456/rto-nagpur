{{-- resources/views/vehicle-fitness/index.blade.php --}}

{{-- Display the vehicle fitness data --}}
@extends('layouts.app')
@section('content')
{{-- Form to upload CSV file --}}
<form action="{{ route('vehicle-fitness.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file" accept=".csv">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload CSV</button>
</form>
<div class="container mx-auto p-4">
    <table class="table-auto w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-right">Sr. No.</th>
                <th class="px-4 py-2 text-right">Vehicle No.</th>
                <th class="px-4 py-2 text-right">Fitness Expiry</th>
                <th class="px-4 py-2 text-right">Mobile Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicleFitnesses as $vehicleFitness)
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 text-right">{{ $vehicleFitness->sr_no }}</td>
                <td class="px-4 py-2 text-right">{{ $vehicleFitness->vehicle_number }}</td>
                <td class="px-4 py-2 text-right">{{ $vehicleFitness->fitness_expiry }}</td>
                <td class="px-4 py-2 text-right">{{ $vehicleFitness->mobile_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
