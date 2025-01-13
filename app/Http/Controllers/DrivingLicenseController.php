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
    // Validate the request
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048', // Ensure the file is a CSV and not larger than 2MB
    ]);

    $file = $request->file('csv_file');
    $data = array();
    $rowCounter = 0;
    $errors = [];

    // Open the file and count the rows
    if (($handle = fopen($file, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $rowCounter++;
        }
        fclose($handle);
    }

    // Check if the row count exceeds 201
    if ($rowCounter > 201) {
        return redirect()->back()->withErrors(['csv_file' => 'CSV file should not have more than 201 rows.']);
    }

    // Reset the row counter and process the file
    $rowCounter = 0;
    if (($handle = fopen($file, 'r')) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($rowCounter > 0) {
                // Validate mobile number and driving license number
                if (strlen($row[0]) != 13) {
                    $errors[] = "Row $rowCounter: Driving license number should be 13 characters.";
                }
                if (strlen($row[3]) != 10 || !is_numeric($row[3])) {
                    $errors[] = "Row $rowCounter: Mobile number should be 10 digits.";
                }

                // If no errors, add the row to the data array
                if (empty($errors)) {
                    $expiryDate = date('Y-m-d', strtotime($row[2]));
                    $data[] = array(
                        'driving_license_number' => $row[0],
                        'owner_name' => $row[1],
                        'expiry_date' => $expiryDate,
                        'mobile_number' => $row[3],
                    );
                }
            }
            $rowCounter++;
        }
        fclose($handle);
    }

    // If there are errors, redirect back with errors
    if (!empty($errors)) {
        return redirect()->back()->withErrors($errors);
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

            $smsMessage = "Dear $ownerName, driving license no. $drivingLicenseNumber suspended from $expiryDate under motor vehicle act. - Dy RTO Wardha";

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

                // Save log to drivingLicenseSmsLog table
                $drivingLicenseSmsLog = new DrivingLicenseSmsLog();
                $drivingLicenseSmsLog->driving_license_id = $drivingLicense->id;
                $drivingLicenseSmsLog->mobile_number = $mobileNumber;
                $drivingLicenseSmsLog->sms_message = $smsMessage;
                $drivingLicenseSmsLog->message_id = $messageId;
                $drivingLicenseSmsLog->job_id = $jobId;
                $drivingLicenseSmsLog->save();
            } catch (\Exception $e) {
                // Log error message and request data
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
        $drivingLicenseSmsLogs = DrivingLicenseSmsLog::all();
        return view('driving-license-logs.index', compact('drivingLicenseSmsLogs'));
    }
}
