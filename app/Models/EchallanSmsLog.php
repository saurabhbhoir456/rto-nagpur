<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EchallanSmsLog extends Model
{
    use HasFactory;

    protected $table = 'echallan_sms_logs';

    protected $fillable = [
        'echallan_id',
        'mobile_number',
        'sms_message',
        'message_id',
        'job_id',
    ];
}
