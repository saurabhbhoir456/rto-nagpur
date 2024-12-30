@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1 class="text-3xl font-bold mb-4">Driving Licenses</h1>
    <div class="flex justify-end mb-4">
    <a href="{{ asset('sample_csvs/driving_license_template.csv') }}" 
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
    <form action="{{ route('driving-licenses.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload CSV</button>
    </form>
    <div class="flex justify-end mb-4">
        <button id="deleteButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-4">Delete Selected</button>
        <form action="{{ route('driving-licenses.sendSms') }}" method="post">
            @csrf
            <input type="hidden" name="driving_license_ids" value="">
            <button id="sendSmsButton" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Send SMS to Selected</button>
        </form>
    </div>
    <table class="table-auto w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-center"><input type="checkbox" id="selectAll" /></th>
                <th class="px-4 py-2 text-center">Driving License Number</th>
                <th class="px-4 py-2 text-center">Owner Name</th>
                <th class="px-4 py-2 text-center">Expiry Date</th>
                <th class="px-4 py-2 text-center">Mobile Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivingLicenses as $drivingLicense)
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2 text-center"><input type="checkbox" name="drivingLicenses[]" value="{{ $drivingLicense->id }}" /></td>
                    <td class="px-4 py-2 text-center">{{ $drivingLicense->driving_license_number }}</td>
                    <td class="px-4 py-2 text-center">{{ $drivingLicense->owner_name }}</td>
                    <td class="px-4 py-2 text-center">{{ $drivingLicense->expiry_date }}</td>
                    <td class="px-4 py-2 text-center">{{ $drivingLicense->mobile_number }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#selectAll').on('click', function() {
                $('input[name="drivingLicenses[]"]').prop('checked', this.checked);
            });

            $('#deleteButton').on('click', function() {
                var selectedDrivingLicenses = [];
                $('input[name="drivingLicenses[]"]:checked').each(function() {
                    selectedDrivingLicenses.push($(this).val());
                });

                if (selectedDrivingLicenses.length > 0) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('driving-licenses.delete') }}',
                        data: { 
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            drivingLicenses: JSON.stringify(selectedDrivingLicenses) 
                        },
                        success: function(data) {
                            console.log(data);
                            // Update the table after the delete request is sent
                            selectedDrivingLicenses.forEach(function(id) {
                                $('tr:has(input[value="' + id + '"])').remove();
                            });
                        }
                    });
                }
            });

            $('#sendSmsButton').on('click', function() {
                var selectedDrivingLicenses = [];
                $('input[name="drivingLicenses[]"]:checked').each(function() {
                    selectedDrivingLicenses.push($(this).val());
                });

                if (selectedDrivingLicenses.length > 0) {
                    $('input[name="driving_license_ids"]').val(JSON.stringify(selectedDrivingLicenses));
                }
            });
        });
    </script>
@endsection
