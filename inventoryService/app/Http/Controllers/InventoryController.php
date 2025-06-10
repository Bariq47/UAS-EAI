<?php

namespace App\Http\Controllers;

use App\Http\Resources\inventoryResource;
use App\Models\inventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = inventoryService::all();
        return new inventoryResource($inventory, 'success', 'Inventory retrieved successfully');
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
            'item_name' => 'required|string|max:255',
            'stock_qty' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return new inventoryResource(null, 'error', $validator->errors());
        }

        $inventory = inventoryService::create($request->all());
        return new inventoryResource($inventory, 'success', 'Inventory created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory = inventoryService::find($id);
        if (!$inventory) {
            return new inventoryResource(null, 'error', 'Item not found');
        }
        return new inventoryResource($inventory, 'success', 'Inventory retrieved successfully');
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
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'stock_qty' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return new inventoryResource(null, 'error', $validator->errors());
        }

        $inventory = inventoryService::find($id);
        if (!$inventory) {
            return new inventoryResource(null, 'error', 'Item not found');
        }

        $inventory->update($request->all());

        return new inventoryResource($inventory, 'success', 'Inventory created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventory = inventoryService::find($id);
        if (!$inventory) {
            return new inventoryResource(null, 'error', 'Item not found');
        }

        $inventory->delete();
        return new inventoryResource(null, 'success', 'Inventory deleted successfully');
    }

    public function decreaseStock(Request $request, $id)
    {
        $inventory = inventoryService::find($id);
        if (!$inventory) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $quantity = $request->input('quantity');
        if ($inventory->stock_qty < $quantity) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $inventory->stock_qty -= $quantity;
        $inventory->save();

        return response()->json(['message' => 'Stock updated successfully']);
    }

    public function increaseStock(Request $request, $id)
    {
        $inventory = inventoryService::find($id);
        if (!$inventory) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $quantity = $request->input('quantity');
        if ($inventory->stock_qty < $quantity) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $inventory->stock_qty += $quantity;
        $inventory->save();

        return response()->json(['message' => 'Stock updated successfully']);
    }
}
