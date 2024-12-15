<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclePermitSmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_permit_id',
        'mobile_number',
        'sms_message',
        'message_id',
        'job_id',
        'error_message',
        'request_data',
    ];
}
