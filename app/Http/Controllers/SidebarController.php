<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SidebarController extends Controller
{
    //
    public function vehicleTax()
    {
        return view('sidebar-pages.vehicle-tax'); // Ensure this view file exists
    }

    public function vehiclePermit()
    {
        return view('sidebar-pages.vehicle-permit'); // Ensure this view file exists
    }

    public function fitnessCertificate()
    {
        return view('sidebar-pages.fitness-certificate'); // Ensure this view file exists
    }

    public function driverLicense()
    {
        return view('sidebar-pages.driver-license'); // Ensure this view file exists
    }
}
