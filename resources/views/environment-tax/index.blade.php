@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1 class="text-3xl font-bold mb-4">Environment Taxes</h1>
    <form action="{{ route('environment-tax.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload CSV</button>
    </form>
    <div class="flex justify-end mb-4">
        <button id="deleteButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-4">Delete Selected</button>
        <form action="{{ route('environment-tax.sendSms') }}" method="post">
            @csrf
            <input type="hidden" name="environment_tax_ids" value="">
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
            @foreach($environmentTaxes as $environmentTax)
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2 text-center"><input type="checkbox" name="environmentTaxes[]" value="{{ $environmentTax->id }}" /></td>
                    <td class="px-4 py-2 text-center">{{ $environmentTax->vehicle_number }}</td>
                    <td class="px-4 py-2 text-center">{{ $environmentTax->mobile_number }}</td>
                    <td class="px-4 py-2 text-center">{{ $environmentTax->expiry_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#selectAll').on('click', function() {
                $('input[name="environmentTaxes[]"]').prop('checked', this.checked);
            });

            $('#deleteButton').on('click', function() {
                var selectedEnvironmentTaxes = [];
                $('input[name="environmentTaxes[]"]:checked').each(function() {
                    selectedEnvironmentTaxes.push($(this).val());
                });

                if (selectedEnvironmentTaxes.length > 0) {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ route('environment-tax.delete') }}',
                        data: { 
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            environmentTaxes: JSON.stringify(selectedEnvironmentTaxes) 
                        },
                        success: function(data) {
                            console.log(data);
                            // Update the table after the delete request is sent
                            selectedEnvironmentTaxes.forEach(function(id) {
                                $('tr:has(input[value="' + id + '"])').remove();
                            });
                        }
                    });
                }
            });

            $('#sendSmsButton').on('click', function() {
                var selectedEnvironmentTaxes = [];
                $('input[name="environmentTaxes[]"]:checked').each(function() {
                    selectedEnvironmentTaxes.push($(this).val());
                });

                if (selectedEnvironmentTaxes.length > 0) {
                    $('input[name="environment_tax_ids"]').val(JSON.stringify(selectedEnvironmentTaxes));
                }
            });
        });
    </script>
@endsection
