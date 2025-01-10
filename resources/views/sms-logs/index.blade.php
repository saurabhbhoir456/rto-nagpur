{{-- Resources/views/sms-logs/index.blade.php --}}
@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('sms-logs.index') }}">
    @csrf
    <div class="flex flex-row mb-4">
        <div class="w-1/2">
            <label for="fromdate" class="text-gray-700">From Date:</label>
            <input type="date" id="fromdate" name="fromdate" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>
        <div class="w-1/2">
            <label for="todate" class="text-gray-700">To Date:</label>
            <input type="date" id="todate" name="todate" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        </div>
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
</form>
{{-- Display the data in a datatable format --}}
<div class="container mx-auto p-4 pt-0 md:p-6 lg:p-12 xl:p-24 mb-4">
<table id="sms-logs-table" class="table-auto w-full text-left mt-0">
        <thead class="bg-gray-100">
            <tr>
            <th class="px-4 py-2">Mobile Number</th>
            <th class="px-4 py-2">Sender ID</th>
            <th class="px-4 py-2">Message</th>
            <th class="px-4 py-2">Submit Date</th>
            <th class="px-4 py-2">Message Status</th>
            <th class="px-4 py-2">Delivery Date</th>
            <th class="px-4 py-2">Alias Message ID</th>
            <th class="px-4 py-2">Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $log)
                <tr>
<td class="border px-4 py-2">{{ $log['MobileNumber'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['SenderId'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['Message'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['SubmitDate'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['MessageStatus'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['DeliveryDate'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['AliasMessageId'] ?? '' }}</td>
<td class="border px-4 py-2">{{ $log['Type'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
   
</div>

{{-- Initialize the datatable --}}
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#sms-logs-table').DataTable({
            "scrollY": "80vh",
            "scrollCollapse": true,
        });
        $('#datatable').DataTable({
            "ordering": false
        });
    });
</script>
{{-- <style>
    table.dataTable thead .sorting {
        background-image: url("https://cdn.datatables.net/1.13.4/images/sort_both.png");
    }
    
    table.dataTable thead .sorting_asc {
        background-image: url("https://cdn.datatables.net/1.13.4/images/sort_asc.png");
    }
    
    table.dataTable thead .sorting_desc {
        background-image: url("https://cdn.datatables.net/1.13.4/images/sort_desc.png");
    }
    </style> --}}
@endsection
