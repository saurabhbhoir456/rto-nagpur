@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Upload Vehicle Tax CSV</h1>

    <!-- Form for uploading CSV -->
    <form action="{{ route('vehicle-tax.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
        @csrf
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">CSV File</label>
            <input type="file" name="csv_file" accept=".csv" required class="mt-1 block w-full">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Upload</button>
    </form>
    <!-- Add records Button -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('vehicle-tax.create') }}" class="px-4 py-2 bg-green-500 text-white rounded">Add Vehicle Tax Record</a>
    </div>
    {{-- Filter Duration and date --}}
    <div class="flex justify-end mb-4">
        <form action="{{ route('vehicle-tax.filter') }}" method="GET" class="flex items-center space-x-4">
            @csrf
            <div class="mr-4">
                <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mr-4">
                <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">End Date:</label>
                <input type="date" name="end_date" id="end_date" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded">Filter</button>
        </form>
    </div>
    <!-- Display Vehicle Tax Data in a Table -->
    <h2 class="text-xl font-bold mb-4">Vehicle Tax Records</h2>

    @if($vehicleTaxes->isEmpty())
        <p>No records found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left py-2 px-4 border-b">Owner Name</th>
                        <th class="text-left py-2 px-4 border-b">Mobile Number</th>
                        <th class="text-left py-2 px-4 border-b">Vehicle Number</th>
                        <th class="text-left py-2 px-4 border-b">Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicleTaxes as $tax)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $tax->owner_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $tax->mobile_number }}</td>
                            <td class="py-2 px-4 border-b">{{ $tax->vehicle_number }}</td>
                            <td class="py-2 px-4 border-b">{{ $tax->due_date }}</td>
                            <td class="py-2 px-4 border-b">
                                <!-- Edit Button -->
                                <a href="{{ route('vehicle-tax.edit', $tax->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <!-- Delete Button -->
                                <form action="{{ route('vehicle-tax.destroy', $tax->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-4">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
