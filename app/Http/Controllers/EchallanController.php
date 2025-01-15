<?php
namespace App\Http\Controllers;

use App\Models\Echallan;
use App\Models\EchallanSmsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $maxRows = 201;
        if (($handle = fopen($file, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($rowCounter > 0) {
                    if ($rowCounter > $maxRows) {
                        return redirect()->back()->with('error', 'CSV file exceeds the maximum number of rows (200).');
                    }
                    if (!preg_match('/^\d{10}$/', $row[1])) {
                        return redirect()->back()->with('error', 'Mobile number must be 10 digits.');
                    }
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
        Echallan::insert($data);
        return redirect()->back()->with('success', 'CSV file uploaded successfully');
    }
    public function deleteEchallans(Request $request)
    {
        $echallanIds = json_decode($request->input('echallans'), true);
        Echallan::whereIn('id', $echallanIds)->delete();
        return response()->json(['success' => 'Echallans deleted successfully']);
    }
    public function sendSms(Request $request)
    {
        $echallanIds = json_decode($request->input('echallan_ids'), true);
        $echallans = Echallan::whereIn('id', $echallanIds)->get();
    
        foreach ($echallans as $echallan) {
            $vehicleNumber = $echallan->vehicle_number;
            $mobileNumber = $echallan->mobile_number;
    
            $smsMessage = "Kind attention, E-challan is pending against your vehicle no. $vehicleNumber. Pay E-challan in 7 days to avoid blacklisting. Ignore if paid. - Dy RTO Wardha.";
    
            $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $senderId = env('SMSGATEWAYHUB_SENDER_ID');
            $entityID = env('SMSGATEWAYHUB_ENTITY_ID');
            $dlttemplateid = env('SMSGATEWAYHUB_DLTEMPLATE_ID_ECHALLAN');
    
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
    
                // Save log to ehallansmslog table
                $echallanSmsLog = new EchallanSmsLog();
                $echallanSmsLog->echallan_id = $echallan->id;
                $echallanSmsLog->mobile_number = $mobileNumber;
                $echallanSmsLog->sms_message = $smsMessage;
                $echallanSmsLog->message_id = $messageId;
                $echallanSmsLog->job_id = $jobId;
                $echallanSmsLog->save();
            } catch (\Exception $e) {
                // Log error message and request data
                $echallanSmsLog = new EchallanSmsLog();
                $echallanSmsLog->echallan_id = $echallan->id;
                $echallanSmsLog->mobile_number = $mobileNumber;
                $echallanSmsLog->sms_message = $smsMessage;
                $echallanSmsLog->error_message = $e->getMessage();
                $echallanSmsLog->request_data = json_encode($postData);
                $echallanSmsLog->save();
            }
        }
    
        return redirect()->route('echallan.index')->with('success', 'SMS sent successfully');
    }
    public function logs()
    {
        $echallanSmsLogs = EchallanSmsLog::all();
        return view('echallan-logs.index', compact('echallanSmsLogs'));
    }
}
