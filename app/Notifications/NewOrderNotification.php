<?php



// app/Notifications/NewOrderNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $temporaryOrderId;
    protected $companyId; 

    public function __construct($temporaryOrderId, $companyId)
    {
        $this->temporaryOrderId = $temporaryOrderId; 
        $this->companyId = $companyId; 
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new order has been placed',
            'order_id' => $this->temporaryOrderId,
            'company_id' => $this->companyId, 
            'url' => route('orders.noti'),
            'type' => 'info',
        ];
    }
}
