<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Echallan extends Model
{
    protected $fillable = [
        'vehicle_number',
        'mobile_number',
        'expiry_date',
    ];
}
