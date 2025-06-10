<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Http;

class TrackingQuery
{
    public function tracking()
    {
        $response = Http::get(env('TRACKING_URL') . '/api/trackings');

        return $response->json('data') ?? [];
    }

    public function trackingId($_, array $args)
    {
        $response = Http::get(env('TRACKING_URL') . '/api/trackings/' . $args['id']);

        if ($response->failed()) {
            return null;
        }
        return $response->json('data');
    }
}
