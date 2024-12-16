<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivingLicense extends Model
{
    protected $fillable = [
        'driving_license_number',
        'owner_name',
        'expiry_date',
        'mobile_number',
    ];
}
