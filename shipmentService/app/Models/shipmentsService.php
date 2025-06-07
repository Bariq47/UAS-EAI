<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipmentsService extends Model
{
    use HasFactory;
    protected $table = 'shipments';

    protected $fillable = [
        'item_id',
        'quantity',
        'destination',
        'status',
    ];
}
