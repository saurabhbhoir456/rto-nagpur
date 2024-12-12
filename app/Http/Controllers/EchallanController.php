<?php
namespace App\Http\Controllers;

use App\Models\Echallan;
use Illuminate\Http\Request;

class EchallanController extends Controller
{
    public function index()
    {
        $echallans = Echallan::all();
        return view('echallan.index', compact('echallans'));
    }
    public function uploadCsv(Request $request)
    {
        $file = $request->file('csv_file');
        $data = array();
        $rowCounter = 0;
        if (($handle = fopen($file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($rowCounter > 0) {
                    $data[] = array(
                        'vehicle_number' => $row[0],
                        'mobile_number' => $row[1],
                        'expiry_date' => $row[2],
                    );
                }
                $rowCounter++;
            }
            fclose($handle);
        }
        Echallan::insert($data);
        return redirect()->back()->with('success', 'CSV file uploaded successfully');
    }
    public function deleteEchallans(Request $request)
    {
        $echallanIds = $request->input('echallans');
        Echallan::whereIn('id', $echallanIds)->delete();
        return response()->json(['success' => 'Echallans deleted successfully']);
    }
}
