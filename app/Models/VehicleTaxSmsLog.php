<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTaxSmsLog extends Model
{
    protected $table = 'vehicletaxsmslog';

    protected $fillable = [
        'vehicle_tax_id',
        'mobile_number',
        'sms_message',
        'message_id',
        'job_id',
        'error_message',
        'request_data',
    ];
}
