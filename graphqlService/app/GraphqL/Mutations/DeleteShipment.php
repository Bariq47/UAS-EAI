<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class DeleteShipment
{
    public function deleteShipment($_, array $args)
    {
        $response = Http::delete(env('SHIPMENT_URL') . '/api/shipments/' . $args['id']);

        if ($response->failed()) {
            throw new \Exception('Failed to delete Shipment.');
        }

        return true;
    }
}
