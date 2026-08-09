<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $pendingCount;

    public function __construct()
    {
        $this->pendingCount = User::where('status', 'pending')->count();
    }

    public function broadcastOn(): array
    {
        // Kirim ke channel 'admin' yang hanya bisa didengar Super Admin
        return [
            new Channel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.pending';
    }

    public function broadcastWith(): array
    {
        return [
            'pending_count' => $this->pendingCount,
            'message'       => 'Ada ' . $this->pendingCount . ' user menunggu persetujuan.',
        ];
    }
}
