<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFitness extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_number',
        'expiry_date',
        'mobile_number',
    ];
}
