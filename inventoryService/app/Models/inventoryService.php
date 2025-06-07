<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventoryService extends Model
{
    use HasFactory;
    protected $table = 'inventories';
    protected $fillable = [
        'item_name',
        'stock_qty',
        'location',
    ];
}
