<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_number',
        'mobile_number',
        'expiry_date',
    ];
}
