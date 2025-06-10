<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateShipment
{
    public function createShipment($_, array $args)
    {
        $response = Http::timeout(60)->post(env('SHIPMENT_URL') . '/api/shipments', [
            'item_id'     => $args['item_id'],
            'quantity'    => $args['quantity'],
            'destination' => $args['destination'],
            'status'      => $args['status'],
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create Shipment.');
        }

        $responseData = $response->json();

        return $responseData['data'];
    }
}
