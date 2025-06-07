<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trackingService extends Model
{
    use HasFactory;
    protected $table = 'trackings';
    protected $fillable = [
        'shipment_id',
        'location',
        'status',
    ];
}
