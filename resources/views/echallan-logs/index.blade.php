@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-4">E-challan Logs</h1>
    <table class="w-full table-auto">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">E-challan ID</th>
                <th class="px-4 py-2">Mobile Number</th>
                <th class="px-4 py-2">SMS Message</th>
                <th class="px-4 py-2">Message ID</th>
                <th class="px-4 py-2">Job ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($echallanSmsLogs as $log)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $log->id }}</td>
                    <td class="px-4 py-2">{{ $log->echallan_id }}</td>
                    <td class="px-4 py-2">{{ $log->mobile_number }}</td>
                    <td class="px-4 py-2">{{ $log->sms_message }}</td>
                    <td class="px-4 py-2">{{ $log->message_id }}</td>
                    <td class="px-4 py-2">{{ $log->job_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection