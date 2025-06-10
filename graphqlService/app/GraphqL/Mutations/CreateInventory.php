<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class CreateInventory
{
    public function createInventory($_, array $args)
    {
        $response = Http::post(env('INVENTORY_URL') . '/api/inventories', [
            'item_name' => $args['item_name'],
            'stock_qty' => $args['stock_qty'],
            'location'  => $args['location'],
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create inventory.');
        }

        return $response->json('data');
    }
}
