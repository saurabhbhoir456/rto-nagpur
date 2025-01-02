<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $apiKey = env('SMSGATEWAYHUB_API_KEY');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.smsgatewayhub.com/api/mt/GetBalance?APIKey=$apiKey");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $balance = json_decode($response, true);
        $balanceParts = explode('|', $balance['Balance']);
        $transBalance = explode(':', $balanceParts[1]);
        return view('dashboard', ['balance' => $transBalance[1]]);
    }
}
