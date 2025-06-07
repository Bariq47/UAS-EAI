<?php

namespace App\Http\Controllers;

use App\Http\Resources\trackingResource;
use App\Models\trackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class trackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tracking = trackingService::all();
        return new trackingResource($tracking, 'success', 'Tracking data retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tracking = trackingService::create([
            'shipment_id' => $request->shipment_id,
            'location' => $request->location,
            'status' => $request->status,
        ]);

        return new trackingResource($tracking, 'success', 'Tracking data created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tracking = trackingService::find($id);
        if (!$tracking) {
            return new trackingResource(null, 'error', 'Tracking not found');
        }
        return new trackingResource($tracking, 'success', 'Tracking data retrieved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tracking = trackingService::find($id);

        $tracking->update([
            'location' => $request->location,
            'status' => $request->status,
        ]);

        Http::patch("http://localhost:8000/api/shipments/{$tracking->shipment_id}/status", [
            'status' => $request->status,
        ]);

        return new trackingResource($tracking, 'success', 'Tracking data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateLocation(Request $request, $id) {}
}
