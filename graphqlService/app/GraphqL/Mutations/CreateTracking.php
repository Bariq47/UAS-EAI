<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class CreateTracking
{
    public function createTracking($_, array $args)
    {
        $response = Http::post(env('TRACKING_URL') . '/api/trackings', [
            'shipment_id' => $args['shipment_id'],
            'location' => $args['location'],
            'status'  => $args['status'],
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to create Tracking.');
        }

        return $response->json('data');
    }
}
