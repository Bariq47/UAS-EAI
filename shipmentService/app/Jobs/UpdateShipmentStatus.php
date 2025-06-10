<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use App\Models\shipmentsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateShipmentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $shipment = shipmentsService::find($this->data['shipment_id']);
        if ($shipment) {
            $shipment->status = $this->data['status'];
            $shipment->save();
            Log::info("Shipment {$shipment->id} updated to {$shipment->status}");
        } else {
            Log::warning("Shipment ID {$this->data['shipment_id']} not found.");
        }
    }
}
