<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class SMSLogsController extends Controller
{
    //
    public function index_post(Request $request)
    {
        $client = new Client();
        // $current_date = date('m/d/Y');
$fromdate = date('m/d/Y', strtotime($request->input('fromdate', date('Y-m-d'))));
$todate = date('m/d/Y', strtotime($request->input('todate', date('Y-m-d'))));
        $response = $client->get('https://www.smsgatewayhub.com/smsapi/mis.aspx', [
  
            'query' => [
                'user' => env('SMSGATEWAYHUB_USERNAME'),
                'password' => env('SMSGATEWAYHUB_PASSWORD'),
                'fromdate' => $fromdate,
                'todate' => $todate,
                // 'fromdate' => '01/08/2025',
                // 'todate' => '01/08/2025',
                'format' => 'json' // Add this if the API supports it
            ],
            'headers' => [
                'Accept' => 'application/json' // Optional: explicitly request JSON
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true); // Decode JSON response to an array
        if (is_null($data)) {
            $data = [];
        }
        // Sort the data based on submit date
        usort($data, function($a, $b) {
            return strtotime($b['SubmitDate']) - strtotime($a['SubmitDate']);
        });
        
        return view('sms-logs.index', compact('data'));
    }

    public function index_get(Request $request)
    {
        $client = new Client();
        $current_date = date('m/d/Y');

        $response = $client->get('https://www.smsgatewayhub.com/smsapi/mis.aspx', [
  
            'query' => [
                'user' => env('SMSGATEWAYHUB_USERNAME'),
                'password' => env('SMSGATEWAYHUB_PASSWORD'),
                'fromdate' => $current_date,
                'todate' => $current_date,
                // 'fromdate' => '01/15/2025',
                // 'todate' => '01/15/2025',
                'format' => 'json' // Add this if the API supports it
            ],
            'headers' => [
                'Accept' => 'application/json' // Optional: explicitly request JSON
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true); // Decode JSON response to an array
        if (is_null($data)) {
            $data = [];
        }
        // Sort the data based on submit date
        usort($data, function($a, $b) {
            return strtotime($b['SubmitDate']) - strtotime($a['SubmitDate']);
        });
        
        return view('sms-logs.index', compact('data'));
    }
}
