@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Add Vehicle Tax Record</h1>

    <!-- Form for adding a new vehicle tax record -->
    <form action="{{ route('vehicle-tax.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
            <input type="text" name="mobile_number" required class="mt-1 block w-full border-gray-300 rounded-md">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Vehicle Number</label>
            <input type="text" name="vehicle_number" required class="mt-1 block w-full border-gray-300 rounded-md">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" name="due_date" required class="mt-1 block w-full border-gray-300 rounded-md">
        </div>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Add Record</button>
        <a href="{{ route('vehicle-tax.index') }}" class="ml-4 text-blue-500">Back to Records</a>
    </form>
</div>
@endsection
