<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShipmentResource;
use App\Models\shipmentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipments = shipmentsService::all();
        return new ShipmentResource($shipments, 'success', 'Shipments retrieved successfully');
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
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'destination' => 'required|string|max:255',
            'status' => 'required|string|in:pending,in_progress,cancel,done',
        ]);

        if ($validator->fails()) {
            return new ShipmentResource(null, 'failed', $validator->errors());
        }

        $cekStokResponse = $this->cekStok($request->item_id, $request->quantity);

        if ($cekStokResponse instanceof ShipmentResource) {
            return $cekStokResponse;
        }

        $shipment = shipmentsService::create($request->all());

        Http::patch('http://localhost:8001/api/inventories/' . $request->item_id . '/decrease', [
            'quantity' => $request->quantity,
        ]);

        Http::post('http://localhost:8002/api/trackings', [
            'shipment_id' => $shipment->id,
            'location' => 'Warehouse',
            'status' => 'ready',
        ]);


        return new ShipmentResource($shipment, 'success', 'Shipment created successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $shipment = shipmentsService::find($id);
        if (!$shipment) {
            return new ShipmentResource(null, 'failed', 'Shipment not found');
        }
        return new ShipmentResource($shipment, 'success', 'Shipment retrieved successfully');
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
        $shipment = shipmentsService::find($id);
        if (!$shipment) {
            return new ShipmentResource(null, 'failed', 'Shipment not found');
        }

        $validator = Validator::make(request()->all(), [
            'item_id' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'destination' => 'required|string|max:255',
            'status' => 'required|string|in:pending,in_progress,cancel,done',
        ]);

        if ($validator->fails()) {
            return new ShipmentResource(null, 'failed', $validator->errors());
        };

        if ($request->item_id != $shipment->item_id || $request->quantity != $shipment->quantity) {
            $cekStokResponse = $this->cekStok($request->item_id, $request->quantity);

            if ($cekStokResponse instanceof ShipmentResource && $cekStokResponse->status === 'failed') {
                return $cekStokResponse;
            }

            Http::patch('http://localhost:8001/api/inventories/' . $shipment->item_id . '/increase', [
                'quantity' => $shipment->quantity,
            ]);

            Http::patch('http://localhost:8001/api/inventories/' . $request->item_id . '/decrease', [
                'quantity' => $request->quantity,
            ]);
        }

        $shipment->update($request->all());

        return new ShipmentResource($shipment, 'success', 'Shipment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shipment = shipmentsService::find($id);
        if (!$shipment) {
            return new ShipmentResource(null, 'failed', 'Shipment not found');
        }
        $shipment->delete();
        return new ShipmentResource(null, 'success', 'Shipment deleted successfully');
    }

    public function cekStok($item_id, $quantity)
    {
        $response = Http::get('http://localhost:8001/api/inventories/' . $item_id);

        if ($response->failed()) {
            return new ShipmentResource(null, 'failed', 'Inventory service unavailable or item not found');
        }

        $data = $response->json();

        if (!isset($data['data']['stock_qty']) || $data['data']['stock_qty'] < $quantity) {
            return new ShipmentResource(null, 'failed', 'Insufficient stock');
        }

        return $data['data']; // ini yang benar, bukan $data->json()

    }

    public function updateStatus(Request $request, string $id)
    {
        $shipment = shipmentsService::find($id);
        if (!$shipment) {
            return new ShipmentResource(null, 'failed', 'Shipment not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,in_progress,cancel,done',
        ]);

        if ($validator->fails()) {
            return new ShipmentResource(null, 'failed', $validator->errors());
        }

        $shipment->update(['status' => $request->status]);

        return new ShipmentResource($shipment, 'success', 'Shipment status updated successfully');
    }
}
