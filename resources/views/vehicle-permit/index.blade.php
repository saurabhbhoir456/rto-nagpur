@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1 class="text-3xl font-bold mb-4">Vehicle Permits</h1>
    <div class="flex justify-end mb-4">
        <a href="{{ asset('sample_csvs\vehicle_permit_template.csv') }}" 
       download
       class="inline-block px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
       Click to Download Sample CSV
        </a>
        </div>
    @if(session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session()->get('success') }}
    </div>
@endif
@if(session()->has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session()->get('error') }}
    </div>
@endif
    <form action="{{ route('vehicle-permits.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload CSV</button>
    </form>
    <div class="flex justify-end mb-4">
        <button id="deleteButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-4">Delete Selected</button>
        <form action="{{ route('vehicle-permits.sendSms') }}" method="post">
            @csrf
            <input type="hidden" name="vehicle_permit_ids" value="">
            <button id="sendSmsButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Send SMS to Selected</button>
        </form>
    </div>
    <table class="table-auto w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-center"><input type="checkbox" id="selectAll" /></th>
                <th class="px-4 py-2 text-center">Vehicle Number</th>
                <th class="px-4 py-2 text-center">Mobile Number</th>
                <th class="px-4 py-2 text-center">Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehiclePermits as $vehiclePermit)
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2 text-center"><input type="checkbox" name="vehiclePermits[]" value="{{ $vehiclePermit->id }}" /></td>
                    <td class="px-4 py-2 text-center">{{ $vehiclePermit->vehicle_number }}</td>
                    <td class="px-4 py-2 text-center">{{ $vehiclePermit->mobile_number }}</td>
                    <td class="px-4 py-2 text-center">{{ $vehiclePermit->expiry_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#selectAll').on('click', function() {
                $('input[name="vehiclePermits[]"]').prop('checked', this.checked);
            });

            $('#deleteButton').on('click', function() {
                var selectedVehiclePermits = [];
                $('input[name="vehiclePermits[]"]:checked').each(function() {
                    selectedVehiclePermits.push($(this).val());
                });

                if (selectedVehiclePermits.length > 0) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('vehicle-permits.delete') }}',
                        data: { 
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            vehiclePermits: JSON.stringify(selectedVehiclePermits) 
                        },
                        success: function(data) {
                            console.log(data);
                            // Update the table after the delete request is sent
                            selectedVehiclePermits.forEach(function(id) {
                                $('tr:has(input[value="' + id + '"])').remove();
                            });
                        }
                    });
                } else {
                    alert('Please select at least one record to delete.');
                }
            });

            $('#sendSmsButton').on('click', function() {
                var selectedVehiclePermits = [];
                $('input[name="vehiclePermits[]"]:checked').each(function() {
                    selectedVehiclePermits.push($(this).val());
                });

                if (selectedVehiclePermits.length > 0) {
                    $('input[name="vehicle_permit_ids"]').val(JSON.stringify(selectedVehiclePermits));
                } else {
                    alert('Please select at least one record to send SMS.');
                }
            });
        });
    </script>
@endsection
