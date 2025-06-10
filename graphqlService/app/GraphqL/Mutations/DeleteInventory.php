<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class DeleteInventory
{
    public function deleteInventory($_, array $args)
    {
        $response = Http::delete(env('INVENTORY_URL') . '/api/inventories/' . $args['id']);

        if ($response->failed()) {
            throw new \Exception('Failed to delete Shipment.');
        }

        return true;
    }
}
