<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class InventoryQuery
{
    public function inventories()
    {
        $response = Http::get(env('INVENTORY_URL') . '/api/inventories');

        return $response->json('data') ?? [];
    }

    public function inventoryId($_, array $args)
    {
        $response = Http::get(env('INVENTORY_URL') . '/api/inventories/' . $args['id']);

        if ($response->failed()) {
            return null;
        }
        return $response->json('data');
    }
}
