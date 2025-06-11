<?php

namespace App\GraphQL\Resolvers;

use Illuminate\Support\Facades\Http;

class ShipmentResolver
{
    public function item($shipment, array $args)
    {
        $itemId = $shipment['item_id'] ?? null;

        if (!$itemId) return null;

        $response = Http::get(env('INVENTORY_URL') . "/api/inventories/{$itemId}");

        if ($response->failed()) return null;

        return $response->json('data');
    }

    public function tracking($shipment, array $args)
    {
        $shipmentId = $shipment['id'] ?? null;

        if (!$shipmentId) return null;

        $response = Http::get(env('TRACKING_URL') . "/api/trackings?shipment_id={$shipmentId}");

        if ($response->failed()) return null;

        // Jika kamu pakai 1-to-1 relasi, ambil yang pertama
        return collect($response->json('data'))->first();
    }
}
