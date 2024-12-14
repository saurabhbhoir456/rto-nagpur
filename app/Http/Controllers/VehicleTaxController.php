<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleTax;
use App\Models\VehicleTaxSmsLog;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VehicleTaxController extends Controller
{
    //
    
public function logs()
{
    $vehicleTaxSmsLogs = VehicleTaxSmsLog::all();
    return view('vehicle-tax-logs.index', compact('vehicleTaxSmsLogs'));
}
    public function destroyMultiple(Request $request)
{
    $ids = $request->input('vehicle_tax_ids');
    VehicleTax::whereIn('id', $ids)->delete();
    return redirect()->route('vehicle-tax.index')->with('success', 'Records deleted successfully');
}

// ...


public function sendSms(Request $request)
{
    $vehicleTaxIds = $request->input('vehicle_tax_ids');
    $vehicleTaxes = VehicleTax::whereIn('id', $vehicleTaxIds)->get();

        foreach ($vehicleTaxes as $vehicleTax) {
            $mobileNumber = $vehicleTax->mobile_number;
            $vehicleNumber = $vehicleTax->vehicle_number;

            $smsMessage = "RTO tax is due for vehicle no. $vehicleNumber. Kindly pay the tax urgently within 7 days to avoid further action. Ignore if paid. - Dy RTO Wardha.";

            $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $senderId = env('SMSGATEWAYHUB_SENDER_ID');
            $entityID = env('SMSGATEWAYHUB_ENTITY_ID');
            $dlttemplateid = env('SMSGATEWAYHUB_DLTEMPLATE_ID_TAX');

            $postData = array(
                "APIKey" => $apiKey,
                "senderid" => $senderId,
                "EntityId" => $entityID,
                "dlttemplateid" => $dlttemplateid,
                "channel" => "2",
                "DCS" => "0",
                "flashsms" => "0",
                "number" => '91' . $mobileNumber,
                "text" => $smsMessage,
                "route" => "1"
            );

            try {
                $response = Http::get($apiUrl, $postData);

                $responseData = json_decode($response->body(), true);
                Log::info('SMS Response Data:', $response->json());
                $messageId = $responseData['MessageData'][0]['MessageId'];
                $jobId = $responseData['JobId'];

                // Save log to vehicle tax sms log table
                $vehicleTaxSmsLog = new VehicleTaxSmsLog();
                $vehicleTaxSmsLog->vehicle_tax_id = $vehicleTax->id;
                $vehicleTaxSmsLog->mobile_number = $mobileNumber;
                $vehicleTaxSmsLog->sms_message = $smsMessage;
                $vehicleTaxSmsLog->message_id = $messageId;
                $vehicleTaxSmsLog->job_id = $jobId;
                $vehicleTaxSmsLog->save();
            } catch (\Exception $e) {
                // Log error message and request data
                $vehicleTaxSmsLog = new VehicleTaxSmsLog();
                $vehicleTaxSmsLog->vehicle_tax_id = $vehicleTax->id;
                $vehicleTaxSmsLog->mobile_number = $mobileNumber;
                $vehicleTaxSmsLog->sms_message = $smsMessage;
                $vehicleTaxSmsLog->error_message = $e->getMessage();
                $vehicleTaxSmsLog->request_data = json_encode($postData);
                $vehicleTaxSmsLog->save();
            }
        }
    return redirect()->route('vehicle-tax.index')->with('success', 'SMS sent successfully');
    }
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
            'mobile_number' => 'required|regex:/^[6-9][0-9]{9}$/',
            'vehicle_number' => 'required|string|max:255',
            'due_date' => 'required|date_format:d-m-Y',
        ]);

        VehicleTax::create($request->only('mobile_number', 'vehicle_number', 'due_date'));

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
            'mobile_number' => 'required|regex:/^[6-9][0-9]{9}$/',
            'vehicle_number' => 'required|string|max:255',
            'due_date' => 'required|date_format:d-m-Y',
        ]);

        $tax = VehicleTax::findOrFail($id);
        $tax->update($request->only('mobile_number', 'vehicle_number', 'due_date'));

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
                'mobile_number' => $row[0],
                'vehicle_number' => $row[1],
                'due_date' => $row[2],
            ], [
                'mobile_number' => 'required|regex:/^[6-9][0-9]{9}$/',
                'vehicle_number' => 'required|string|max:255',
                'due_date' => 'required|date_format:d-m-Y',
            ]);

            if ($validator->fails()) {
                // Skip this row or handle the error
                continue;
            }

            // Insert the data into the database
            VehicleTax::create([
                'mobile_number' => $row[0],
                'vehicle_number' => $row[1],
                'due_date' => Carbon::createFromFormat('d-m-Y', $row[2]),
            ]);
        }

        // Close the file
        fclose($file);

        return redirect()->route('vehicle-tax.index')->with('success', 'CSV uploaded successfully!');
    }
    public function filterVehicleTaxes(Request $request)
    {
        $duration = $request->input('duration');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $vehicleTaxes = VehicleTax::where('due_date', '>=', $startDate)
            ->where('due_date', '<=', $endDate);

        if ($duration) {
            $vehicleTaxes = $vehicleTaxes->whereDate('due_date', '>=', now()->subDays($duration));
        }

        $vehicleTaxes = $vehicleTaxes->get();

        return view('vehicle-tax.index', compact('vehicleTaxes'));
    }
}
