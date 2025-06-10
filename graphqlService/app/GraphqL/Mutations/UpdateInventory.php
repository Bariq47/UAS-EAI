<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class UpdateInventory
{
    public function updateInventory($_, array $args)
    {
        $payload = array_filter([
            'item_name'     => $args['item_name'],
            'stock_qty'    => $args['stock_qty'],
            'location'      => $args['location'],
        ]);

        $response = Http::put(env('INVENTORY_URL') . '/api/inventories/' . $args['id'], $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to Update Inventory.');
        }

        return $response->json('data');
    }
}
