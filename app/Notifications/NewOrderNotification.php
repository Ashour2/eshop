<?php
namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array {
        return [
            'type'    => 'new_order',
            'icon'    => '🛒',
            'title'   => 'طلب جديد #' . $this->order->id,
            'message' => $this->order->customer_name . ' — $' . number_format($this->order->total, 2),
            'url'     => '/admin/orders/' . $this->order->id,
        ];
    }
}
