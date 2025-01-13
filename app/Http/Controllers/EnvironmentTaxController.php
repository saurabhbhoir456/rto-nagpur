<?php
namespace App\Http\Controllers;

use App\Models\EnvironmentTax;
use App\Models\EnvironmentTaxSmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EnvironmentTaxController extends Controller
{
    public function index()
    {
        $environmentTaxes = EnvironmentTax::all();
        return view('environment-tax.index', compact('environmentTaxes'));
    }
    
    
    public function uploadCsv(Request $request)
    {
        $file = $request->file('csv_file');
        $data = array();
        $rowCounter = 0;
        $maxRows = 201;
    
        if (($handle = fopen($file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($rowCounter > 0) {
                    if ($rowCounter > $maxRows) {
                        return back()->withErrors(['csv_file' => 'CSV file should not exceed 201 rows.']);
                    }
    
                    $mobileNumber = $row[1];
                    if (!preg_match('/^\d{10}$/', $mobileNumber)) {
                        return back()->withErrors(['csv_file' => 'Mobile number should be 10 digits long.']);
                    }
    
                    $expiryDate = date('Y-m-d', strtotime($row[2]));
                    $data[] = array(
                        'vehicle_number' => $row[0],
                        'mobile_number' => $mobileNumber,
                        'expiry_date' => $expiryDate,
                    );
                }
                $rowCounter++;
            }
            fclose($handle);
        }
    
        // Process the $data array as needed
        // ...
        EnvironmentTax::insert($data);
        return back()->with('success', 'CSV file uploaded successfully.');
    }

    public function deleteEnvironmentTaxes(Request $request)
    {
        $environmentTaxIds = json_decode($request->input('environmentTaxes'), true);
        EnvironmentTax::whereIn('id', $environmentTaxIds)->delete();
        return response()->json(['success' => 'Environment taxes deleted successfully']);
    }
    public function sendSms(Request $request)
    {
        $environmentTaxIds = json_decode($request->input('environment_tax_ids'), true);
        $environmentTaxes = EnvironmentTax::whereIn('id', $environmentTaxIds)->get();
    
        foreach ($environmentTaxes as $environmentTax) {
            $vehicleNumber = $environmentTax->vehicle_number;
            $mobileNumber = $environmentTax->mobile_number;
    
            $smsMessage = "RTO Environment tax is due for vehicle no. $vehicleNumber. Kindly pay the tax urgently within 7 days to avoid further action. Ignore if paid. - Dy RTO Wardha.";
    
            $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $senderId = env('SMSGATEWAYHUB_SENDER_ID');
            $entityID = env('SMSGATEWAYHUB_ENTITY_ID');
            $dlttemplateid = env('SMSGATEWAYHUB_DLTEMPLATE_ID_ENVIRONMENT_TAX');
    
            $postData = array(
                "APIKey" => $apiKey,
                "senderid" => $senderId,
                "EntityId" => $entityID,
                "dlttemplateid" => $dlttemplateid,
                "channel" => "2",
                "DCS" => "0",
                "flashsms" => "0",
                "number" => '91'.$mobileNumber,
                "text" => $smsMessage,
                "route" => "1"
            );
    
            try {
                $response = Http::get($apiUrl, $postData);
    
                $responseData = json_decode($response->body(), true);
                Log::info('SMS Response Data:', $response->json());
                $messageId = $responseData['MessageData'][0]['MessageId'];
                $jobId = $responseData['JobId'];
    
                // Save log to environment tax sms log table
                // Note: You may need to create an EnvironmentTaxSmsLog model and migration
                $environmentTaxSmsLog = new EnvironmentTaxSmsLog();
                $environmentTaxSmsLog->environment_tax_id = $environmentTax->id;
                $environmentTaxSmsLog->mobile_number = $mobileNumber;
                $environmentTaxSmsLog->sms_message = $smsMessage;
                $environmentTaxSmsLog->message_id = $messageId;
                $environmentTaxSmsLog->job_id = $jobId;
                $environmentTaxSmsLog->save();
            } catch (\Exception $e) {
                // Log error message and request data
                // Note: You may need to create an EnvironmentTaxSmsLog model and migration
                $environmentTaxSmsLog = new EnvironmentTaxSmsLog();
                $environmentTaxSmsLog->environment_tax_id = $environmentTax->id;
                $environmentTaxSmsLog->mobile_number = $mobileNumber;
                $environmentTaxSmsLog->sms_message = $smsMessage;
                $environmentTaxSmsLog->error_message = $e->getMessage();
                $environmentTaxSmsLog->request_data = json_encode($postData);
                $environmentTaxSmsLog->save();
            }
        }
    
        return redirect()->route('environment-tax.index')->with('success', 'SMS sent successfully');
    }
    public function logs()
    {
        // Note: You may need to create an EnvironmentTaxSmsLog model and migration
        $environmentTaxSmsLogs = EnvironmentTaxSmsLog::all();
        return view('environment-tax-logs.index', compact('environmentTaxSmsLogs'));
    }
}
