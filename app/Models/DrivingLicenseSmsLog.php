<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivingLicenseSmsLog extends Model
{
    protected $fillable = [
        'driving_license_id',
        'mobile_number',
        'sms_message',
        'message_id',
        'job_id',
        'error_message',
        'request_data',
    ];
}
