<?php

namespace App\Http\Controllers;

use App\Models\DrivingLicense;
use App\Models\DrivingLicenseSmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DrivingLicenseController extends Controller
{
    public function index()
    {
        $drivingLicenses = DrivingLicense::all();
        return view('driving-licenses.index', compact('drivingLicenses'));
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
                        'driving_license_number' => $row[0],
                        'owner_name' => $row[1],
                        'expiry_date' => $expiryDate,
                        'mobile_number' => $row[3],
                    );
                }
                $rowCounter++;
            }
            fclose($handle);
        }
        DrivingLicense::insert($data);
        return redirect()->back()->with('success', 'CSV file uploaded successfully');
    }

    public function deleteDrivingLicenses(Request $request)
    {
        $drivingLicenseIds = json_decode($request->input('drivingLicenses'), true);
        DrivingLicense::whereIn('id', $drivingLicenseIds)->delete();
        return response()->json(['success' => 'Driving licenses deleted successfully']);
    }

    public function sendSms(Request $request)
    {
        $drivingLicenseIds = json_decode($request->input('driving_license_ids'), true);
        $drivingLicenses = DrivingLicense::whereIn('id', $drivingLicenseIds)->get();

        foreach ($drivingLicenses as $drivingLicense) {
            $drivingLicenseNumber = $drivingLicense->driving_license_number;
            $ownerName = $drivingLicense->owner_name;
            $mobileNumber = $drivingLicense->mobile_number;
            $expiryDate = $drivingLicense->expiry_date;

            $smsMessage = "Dear $ownerName, driving license no. $drivingLicenseNumber suspended from $expiryDate under motor vehicle act. For further details visit Dy RTO Wardha. - Dy RTO Wardha.";

            $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $senderId = env('SMSGATEWAYHUB_SENDER_ID');
            $entityID = env('SMSGATEWAYHUB_ENTITY_ID');
            $dlttemplateid = env('SMSGATEWAYHUB_DLTEMPLATE_ID_DRIVING_LICENSE');

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

                // Save log to drivinglicensesmslog table
                // Note: You need to create the drivinglicensesmslog table and model
                $drivingLicenseSmsLog = new DrivingLicenseSmsLog();
                $drivingLicenseSmsLog->driving_license_id = $drivingLicense->id;
                $drivingLicenseSmsLog->mobile_number = $mobileNumber;
                $drivingLicenseSmsLog->sms_message = $smsMessage;
                $drivingLicenseSmsLog->message_id = $messageId;
                $drivingLicenseSmsLog->job_id = $jobId;
                $drivingLicenseSmsLog->save();
            } catch (\Exception $e) {
                // Log error message and request data
                // Note: You need to create the drivinglicensesmslog table and model
                $drivingLicenseSmsLog = new DrivingLicenseSmsLog();
                $drivingLicenseSmsLog->driving_license_id = $drivingLicense->id;
                $drivingLicenseSmsLog->mobile_number = $mobileNumber;
                $drivingLicenseSmsLog->sms_message = $smsMessage;
                $drivingLicenseSmsLog->error_message = $e->getMessage();
                $drivingLicenseSmsLog->request_data = json_encode($postData);
                $drivingLicenseSmsLog->save();
            }
        }

        return redirect()->route('driving-licenses.index')->with('success', 'SMS sent successfully');
    }

    public function logs()
    {
        // Note: You need to create the drivinglicensesmslog table and model
        $drivingLicenseSmsLogs = DrivingLicenseSmsLog::all();
        return view('driving-license-logs.index', compact('drivingLicenseSmsLogs'));
    }
}
