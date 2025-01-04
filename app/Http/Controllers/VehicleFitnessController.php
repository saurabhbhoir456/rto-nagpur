<?php
// VehicleFitnessController.php content
namespace App\Http\Controllers;
    
use App\Models\VehicleFitness;
use App\Models\VehicleFitnessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class VehicleFitnessController extends Controller
{

    
    
   
        public function index()
        {
            $vehicleFitnesses = VehicleFitness::all();
            return view('vehicle-fitness.index', compact('vehicleFitnesses'));
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
            VehicleFitness::insert($data);
            return redirect()->back()->with('success', 'CSV file uploaded successfully');
        }
    
        public function deleteVehicleFitnesses(Request $request)
        {
            $vehicleFitnessIds = json_decode($request->input('vehicleFitnesses'), true);
            VehicleFitness::whereIn('id', $vehicleFitnessIds)->delete();
            return response()->json(['success' => 'Vehicle fitnesses deleted successfully']);
        }
    
        public function sendSms(Request $request)
        {
            $vehicleFitnessIds = json_decode($request->input('vehicle_fitness_ids'), true);
            $vehicleFitnesses = VehicleFitness::whereIn('id', $vehicleFitnessIds)->get();
    
            foreach ($vehicleFitnesses as $vehicleFitness) {
                $vehicleNumber = $vehicleFitness->vehicle_number;
                $mobileNumber = $vehicleFitness->mobile_number;
    
                $smsMessage = "Fitness Certificate of your vehicle no. $vehicleNumber expired. Renew urgently to avoid further action. Ignore if renewed. -Dy RTO Wardha.";

                $apiUrl = "https://www.smsgatewayhub.com/api/mt/SendSMS";
                $apiKey = env('SMSGATEWAYHUB_API_KEY');
                $senderId = env('SMSGATEWAYHUB_SENDER_ID');
                $entityID = env('SMSGATEWAYHUB_ENTITY_ID');
                $dlttemplateid = env('SMSGATEWAYHUB_DLTEMPLATE_ID_VEHICLE_FITNESS');
    
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
    
                    // Save log to vehiclefitnesssmslog table
                    $vehicleFitnessSmsLog = new VehicleFitnessLog();
                    $vehicleFitnessSmsLog->vehicle_fitness_id = $vehicleFitness->id;
                    $vehicleFitnessSmsLog->mobile_number = $mobileNumber;
                    $vehicleFitnessSmsLog->sms_message = $smsMessage;
                    $vehicleFitnessSmsLog->message_id = $messageId;
                    $vehicleFitnessSmsLog->job_id = $jobId;
                    $vehicleFitnessSmsLog->save();
                } catch (\Exception $e) {
                    // Log error message and request data
                    $vehicleFitnessSmsLog = new VehicleFitnessLog();
                    $vehicleFitnessSmsLog->vehicle_fitness_id = $vehicleFitness->id;
                    $vehicleFitnessSmsLog->mobile_number = $mobileNumber;
                    $vehicleFitnessSmsLog->sms_message = $smsMessage;
                    $vehicleFitnessSmsLog->error_message = $e->getMessage();
                    $vehicleFitnessSmsLog->request_data = json_encode($postData);
                    $vehicleFitnessSmsLog->save();
                }
            }
    
            return redirect()->route('vehicle-fitness.index')->with('success', 'SMS sent successfully');
        }
    
        public function logs()
        {
            $vehicleFitnessSmsLogs = VehicleFitnessLog::all();
            return view('vehicle-fitness-logs.index', compact('vehicleFitnessSmsLogs'));
        }
    }
