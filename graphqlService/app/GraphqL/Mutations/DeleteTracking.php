<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

class DeleteTracking
{
    public function deleteTracking($_, array $args)
    {
        $response = Http::delete(env('TRACKING_URL') . '/api/trackings/' . $args['id']);

        if ($response->failed()) {
            throw new \Exception('Failed to delete Tracking.');
        }

        return true;
    }
}
