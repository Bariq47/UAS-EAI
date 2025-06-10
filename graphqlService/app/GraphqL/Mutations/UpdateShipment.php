<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class UpdateShipment
{
    public function updateShipment($_, array $args)
    {
        $payload = array_filter([
            'item_id'     => $args['item_id'],
            'quantity'    => $args['quantity'],
            'destination' => $args['destination'],
            'status'      => $args['status'],
        ]);

        $response = Http::put(env('SHIPMENT_URL') . '/api/shipments/' . $args['id'], $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to Update Shipment.');
        }

        return $response->json('data');
    }
}
