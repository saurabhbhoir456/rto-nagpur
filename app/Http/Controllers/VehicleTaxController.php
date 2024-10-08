<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleTax;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VehicleTaxController extends Controller
{
    //
    public function index()
    {
        $vehicleTaxes = VehicleTax::all();

        // Pass the data to the view
        
        return view('vehicle-tax.index', compact('vehicleTaxes'));
        // return view('sidebar-pages.vehicle-tax');
    }
    // Add a new record
    public function create()
{
    return view('vehicle-tax.create');
}
    public function store(Request $request)
    {
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'mobile_number' => 'required|regex:/^[6-9][0-9]{9}$/',
            'due_date' => 'required|date_format:Y-m-d',
        ]);

        VehicleTax::create($request->only('owner_name', 'mobile_number', 'due_date'));

        return redirect()->route('vehicle-tax.index')->with('success', 'Record added successfully!');
    }

    public function edit($id)
    {
        $tax = VehicleTax::findOrFail($id);
        return view('vehicle-tax.edit', compact('tax'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'owner_name' => 'required|string|max:255',
            'mobile_number' => 'required|regex:/^[6-9][0-9]{9}$/',
            'due_date' => 'required|date_format:Y-m-d',
        ]);

        $tax = VehicleTax::findOrFail($id);
        $tax->update($request->only('owner_name', 'mobile_number', 'due_date'));

        return redirect()->route('vehicle-tax.index')->with('success', 'Record updated successfully!');
    }
    public function destroy($id)
    {
        $tax = VehicleTax::findOrFail($id);
        $tax->delete();

        return redirect()->route('vehicle-tax.index')->with('success', 'Record deleted successfully!');
    }

    public function upload(Request $request)
    {
        // Validate the request to ensure a file is uploaded
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Open the uploaded CSV file
        $file = fopen($request->file('csv_file'), 'r');
        
        // Skip the first row if it contains column headers
        $isFirstRow = true;

        while (($row = fgetcsv($file, 1000, ",")) !== false) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            // Validate each row
            $validator = Validator::make([
                'owner_name' => $row[0],
                'mobile_number' => $row[1],
                'due_date' => $row[2],
            ], [
                'owner_name' => 'required|string|max:255',
                'mobile_number' => 'required|regex:/^[6-9][0-9]{9}$/',
                'due_date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                // Skip this row or handle the error
                continue;
            }

            // Insert the data into the database
            VehicleTax::create([
                'owner_name' => $row[0],
                'mobile_number' => $row[1],
                'due_date' => Carbon::parse($row[2]),
            ]);
        }

        // Close the file
        fclose($file);

        return redirect()->route('vehicle-tax.index')->with('success', 'CSV uploaded successfully!');
    }
}
