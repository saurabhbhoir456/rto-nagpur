@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1 class="text-3xl font-bold mb-4">Vehicle Fitness</h1>
    @if(session()->has('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session()->get('success') }}
    </div>
@endif
    <form action="{{ route('vehicle-fitness.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload CSV</button>
    </form>
    <div class="flex justify-end mb-4">
        <button id="deleteButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-4">Delete Selected</button>
        <form action="{{ route('vehicle-fitness.sendSms') }}" method="post">
            @csrf
            <input type="hidden" name="vehicle_fitness_ids" value="">
            <button id="sendSmsButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Send SMS to Selected</button>
        </form>
    </div>
    <table class="table-auto w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-center"><input type="checkbox" id="selectAll" /></th>
                <th class="px-4 py-2 text-center">Vehicle Number</th>
                <th class="px-4 py-2 text-center">Fitness Expiry</th>
                <th class="px-4 py-2 text-center">Mobile Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicleFitnesses as $vehicleFitness)
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2 text-center"><input type="checkbox" name="vehicleFitnesses[]" value="{{ $vehicleFitness->id }}" /></td>
                    <td class="px-4 py-2 text-center">{{ $vehicleFitness->vehicle_number }}</td>
                    <td class="px-4 py-2 text-center">{{ $vehicleFitness->expiry_date }}</td>
                    <td class="px-4 py-2 text-center">{{ $vehicleFitness->mobile_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#selectAll').on('click', function() {
                $('input[name="vehicleFitnesses[]"]').prop('checked', this.checked);
            });

            $('#deleteButton').on('click', function() {
                var selectedVehicleFitnesses = [];
                $('input[name="vehicleFitnesses[]"]:checked').each(function() {
                    selectedVehicleFitnesses.push($(this).val());
                });

                if (selectedVehicleFitnesses.length > 0) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('vehicle-fitness.delete') }}',
                        data: { 
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            vehicleFitnesses: JSON.stringify(selectedVehicleFitnesses) 
                        },
                        success: function(data) {
                            console.log(data);
                            // Update the table after the delete request is sent
                            selectedVehicleFitnesses.forEach(function(id) {
                                $('tr:has(input[value="' + id + '"])').remove();
                            });
                        }
                    });
                }
            });

            $('#sendSmsButton').on('click', function() {
                var selectedVehicleFitnesses = [];
                $('input[name="vehicleFitnesses[]"]:checked').each(function() {
                    selectedVehicleFitnesses.push($(this).val());
                });

                if (selectedVehicleFitnesses.length > 0) {
                    $('input[name="vehicle_fitness_ids"]').val(JSON.stringify(selectedVehicleFitnesses));
                }
            });
        });
    </script>
@endsection
