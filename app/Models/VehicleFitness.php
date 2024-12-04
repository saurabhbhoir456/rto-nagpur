<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFitness extends Model
{
    use HasFactory;

    protected $fillable = [
        'sr_no',
        'vehicle_number',
        'fitness_expiry',
        'mobile_number',
    ];
}
