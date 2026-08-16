<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\WoComment;

class WoDiscussionNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $type;

    /**
     * Create a new notification instance.
     * $type can be 'post' or 'comment'
     */
    public function __construct(WoComment $comment, $type = 'post')
    {
        $this->comment = $comment;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $author = $this->comment->user->nama_lengkap;
        $wo = $this->comment->workOrder->no_wo;
        
        $message = $this->type === 'post' 
            ? "{$author} membuat postingan diskusi baru di {$wo}."
            : "{$author} membalas postingan di {$wo}.";

        return [
            'message' => $message,
            'work_order_id' => $this->comment->work_order_id,
            'comment_id' => $this->comment->id,
            'url' => route('work-orders.show', $this->comment->workOrder->getRouteKey()) . '#wo-discussion-card'
        ];
    }
}
