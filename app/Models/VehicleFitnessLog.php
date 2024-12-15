<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFitnessLog extends Model
{
    use HasFactory;
    protected $table = 'vehicle_fitness_logs';
    protected $fillable = [
        'vehicle_fitness_id',
        'mobile_number',
        'sms_message',
        'message_id',
        'job_id',
    ];

    // Add any additional properties and methods as needed
}
