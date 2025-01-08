<?php

namespace App\Http\Controllers;

use App\Models\VehiclePermit;
use App\Models\VehiclePermitSmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VehiclePermitController extends Controller
{
    public function index()
    {
        $vehiclePermits = VehiclePermit::all();
        return view('vehicle-permit.index', compact('vehiclePermits'));
    }

    public function uploadCsv(Request $request)
    {
        $file = $request->file('csv_file');
        $data = array();
        $rowCounter = 0;
        if (($handle = fopen($file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($rowCounter > 0) {
                    $expiryDate = date('Y-m-d', strtotime($row[2]));
                    $data[] = array(
                        'vehicle_number' => $row[0],
                        'mobile_number' => $row[1],
                        'expiry_date' => $expiryDate,
                    );
                }
                $rowCounter++;
            }
            fclose($handle);
        }
        VehiclePermit::insert($data);
        return redirect()->back()->with('success', 'CSV file uploaded successfully');
    }

    public function deleteVehiclePermits(Request $request)
    {
        $vehiclePermitIds = json_decode($request->input('vehiclePermits'), true);
        VehiclePermit::whereIn('id', $vehiclePermitIds)->delete();
        return response()->json(['success' => 'Vehicle permits deleted successfully']);
    }

    public function sendSms(Request $request)
    {
        $vehiclePermitIds = json_decode($request->input('vehicle_permit_ids'), true);

        $vehiclePermits = VehiclePermit::whereIn('id', $vehiclePermitIds)->get();

        foreach ($vehiclePermits as $vehiclePermit) {
            $vehicleNumber = $vehiclePermit->vehicle_number;
            $mobileNumber = $vehiclePermit->mobile_number;

            $smsMessage = "Permit of your vehicle no. $vehicleNumber expired. Renew the permit of your vehicle urgently to avoid further action. Ignore if renewed. - Dy RTO Wardha.

            $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $senderId = env('SMSGATEWAYHUB_SENDER_ID');
            $entityID = env('SMSGATEWAYHUB_ENTITY_ID');
            $dlttemplateid = env('SMSGATEWAYHUB_DLTEMPLATE_ID_VEHICLE_PERMIT');

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

                // Save log to vehiclePermitSmsLog table
                $vehiclePermitSmsLog = new VehiclePermitSmsLog();
                $vehiclePermitSmsLog->vehicle_permit_id = $vehiclePermit->id;
                $vehiclePermitSmsLog->mobile_number = $mobileNumber;
                $vehiclePermitSmsLog->sms_message = $smsMessage;
                $vehiclePermitSmsLog->message_id = $messageId;
                $vehiclePermitSmsLog->job_id = $jobId;
                $vehiclePermitSmsLog->save();
            } catch (\Exception $e) {
                // Log error message and request data
                $vehiclePermitSmsLog = new VehiclePermitSmsLog();
                $vehiclePermitSmsLog->vehicle_permit_id = $vehiclePermit->id;
                $vehiclePermitSmsLog->mobile_number = $mobileNumber;
                $vehiclePermitSmsLog->sms_message = $smsMessage;
                $vehiclePermitSmsLog->error_message = $e->getMessage();
                $vehiclePermitSmsLog->request_data = json_encode($postData);
                $vehiclePermitSmsLog->save();
            }
        }

        return redirect()->route('vehicle-permit.index')->with('success', 'SMS sent successfully');
    }

    public function logs()
    {
        $vehiclePermitSmsLogs = VehiclePermitSmsLog::all();
        return view('vehicle-permit-logs.index', compact('vehiclePermitSmsLogs'));
    }
}
