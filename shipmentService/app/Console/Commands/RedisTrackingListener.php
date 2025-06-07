<?php

namespace App\Console\Commands;

use App\Models\shipmentsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisTrackingListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:listen-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to tracking updates and update shipment status';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        Redis::subscribe(['tracking_updates'], function ($message) {
            $data = json_decode($message, true);

             $shipment = shipmentsService::find($data['shipment_id']);
            if ($shipment) {
                $shipment->status = $data['status'];
                $shipment->save();

                $this->info("Shipment {$shipment->id} updated to {$shipment->status}");
            } else {
                $this->error("Shipment ID {$data['shipment_id']} not found.");
            }
        });
    }
}
