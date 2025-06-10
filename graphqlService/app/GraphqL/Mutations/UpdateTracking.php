<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class UpdateTracking
{
    public function updateTracking($_, array $args)
    {
        $payload = array_filter([
            'shipment_id'     => $args['shipment_id'],
            'location'    => $args['location'],
            'status'      => $args['status'],
        ]);

        $response = Http::put(env('TRACKING_URL') . '/api/trackings/' . $args['id'], $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to Update Tracking.');
        }

        return $response->json('data');
    }
}
