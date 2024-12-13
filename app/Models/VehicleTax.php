<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTax extends Model
{
    use HasFactory;
    protected $fillable = ['mobile_number', 'vehicle_number', 'due_date'];
}
