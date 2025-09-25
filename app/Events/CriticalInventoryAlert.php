<?php

namespace App\Events;

use App\Models\InventoryAlert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CriticalInventoryAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alert;

    /**
     * Create a new event instance.
     */
    public function __construct(InventoryAlert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('inventory-alerts'),
            new PrivateChannel('vendor.' . $this->alert->inventory->product->vendor_id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->alert->id,
            'type' => $this->alert->type,
            'priority' => $this->alert->priority,
            'message' => $this->alert->message,
            'product_name' => $this->alert->inventory->product->name ?? 'Unknown Product',
            'product_sku' => $this->alert->inventory->product->sku ?? 'Unknown SKU',
            'current_stock' => $this->alert->inventory->quantity ?? 0,
            'created_at' => $this->alert->created_at->toISOString(),
        ];
    }

    /**
     * Get the event name for broadcasting.
     */
    public function broadcastAs(): string
    {
        return 'critical.inventory.alert';
    }
}
