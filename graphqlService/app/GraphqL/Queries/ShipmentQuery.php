<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class ShipmentQuery
{
    public function shipment()
    {
        $response = Http::get(env('SHIPMENT_URL') . '/api/shipments');

        return $response->json('data') ?? [];
    }

    public function shipmentId($_, array $args)
    {
        $response = Http::get(env('SHIPMENT_URL') . '/api/shipments/' . $args['id']);

        if ($response->failed()) {
            return null;
        }
        return $response->json('data');
    }
}
