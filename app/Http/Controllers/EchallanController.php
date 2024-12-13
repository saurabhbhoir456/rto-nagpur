<?php
namespace App\Http\Controllers;

use App\Models\Echallan;
use App\Models\EchallanSmsLog;
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
        $echallanIds = $request->input('echallans');
        Echallan::whereIn('id', $echallanIds)->delete();
        return response()->json(['success' => 'Echallans deleted successfully']);
    }
    public function sendSms(Request $request)
    {
        $echallanIds = $request->input('echallan_ids');
        $echallans = Echallan::whereIn('id', $echallanIds)->get();
    
        foreach ($echallans as $echallan) {
            $vehicleNumber = $echallan->vehicle_number;
            $mobileNumber = $echallan->mobile_number;
    
            $smsMessage = "Kind attention, E-challan is pending against your vehicle no. $vehicleNumber. Pay E-challan in 7 days to avoid blacklisting. Ignore if paid. - Dy RTO Wardha.";
    
            $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
            $apiKey = env('SMSGATEWAYHUB_API_KEY');
            $senderId = env('SMSGATEWAYHUB__SENDER_ID');
    
            $postData = array(
                "APIKey" => $apiKey,
                "senderid" => $senderId,
                "channel" => "2",
                "DCS" => "0",
                "flashsms" => "0",
                "number" => $mobileNumber,
                "text" => $smsMessage,
                "route" => "1"
            );
    
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            $response = curl_exec($ch);
            curl_close($ch);
    
            $responseData = json_decode($response, true);
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
        }
    
        return response()->json(['message' => 'SMS sent successfully']);
    }
    public function logs()
    {
        $echallanSmsLogs = EchallanSmsLog::all();
        return view('echallan-logs.index', compact('echallanSmsLogs'));
    }
}
