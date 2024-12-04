<?php
// VehicleFitnessController.php content
namespace App\Http\Controllers;

use App\Models\VehicleFitness;
use Illuminate\Http\Request;

class VehicleFitnessController extends Controller
{
    public function index()
    {
        $vehicleFitnesses = VehicleFitness::all();
        return view('vehicle-fitness.index', compact('vehicleFitnesses'));
    }

    public function create()
    {
        return view('vehicle-fitness.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required',
            'fitness_date' => 'required',
            'expiry_date' => 'required',
        ]);

        VehicleFitness::create($request->all());

        return redirect()->route('vehicle-fitness.index')->with('success', 'Vehicle fitness created successfully');
    }

    public function show(VehicleFitness $vehicleFitness)
    {
        return view('vehicle-fitness.show', compact('vehicleFitness'));
    }

    public function edit(VehicleFitness $vehicleFitness)
    {
        return view('vehicle-fitness.edit', compact('vehicleFitness'));
    }

    public function update(Request $request, VehicleFitness $vehicleFitness)
    {
        $request->validate([
            'vehicle_id' => 'required',
            'fitness_date' => 'required',
            'expiry_date' => 'required',
        ]);

        $vehicleFitness->update($request->all());

        return redirect()->route('vehicle-fitness.index')->with('success', 'Vehicle fitness updated successfully');
    }

    public function destroy(VehicleFitness $vehicleFitness)
    {
        $vehicleFitness->delete();

        return redirect()->route('vehicle-fitness.index')->with('success', 'Vehicle fitness deleted successfully');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $csvFile = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($csvFile));

        foreach ($csvData as $row) {
            VehicleFitness::create([
                'sr_no' => $row[0],
                'vehicle_number' => $row[1],
                'fitness_expiry' => date('Y-m-d', strtotime($row[2])),
                'mobile_number' => $row[3],
            ]);
        }

        return redirect()->route('vehicle-fitness.index')->with('success', 'Vehicle fitness data imported successfully');
    }
}
