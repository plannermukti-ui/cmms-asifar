<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ToolStockRequestNotification extends Notification
{
    use Queueable;

    protected $request;
    protected $title;
    protected $message;

    public function __construct($request, $title, $message)
    {
        $this->request = $request;
        $this->title = $title;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => route('tool-stock-requests.show', $this->request->id),
            'icon' => 'tools'
        ];
    }
}
