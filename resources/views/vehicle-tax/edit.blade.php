@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Vehicle Tax Record</h1>

    <form action="{{ route('vehicle-tax.update', $tax->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Owner Name</label>
            <input type="text" name="owner_name" value="{{ old('owner_name', $tax->owner_name) }}" required class="mt-1 block w-full">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Mobile Number</label>
            <input type="text" name="mobile_number" value="{{ old('mobile_number', $tax->mobile_number) }}" required class="mt-1 block w-full">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date', $tax->due_date) }}" required class="mt-1 block w-full">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Update</button>
    </form>
</div>
@endsection
