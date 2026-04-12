<?php
namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product) {}

    public function via(object $notifiable): array {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array {
        return [
            'type'    => 'low_stock',
            'icon'    => '⚠️',
            'title'   => 'تحذير: مخزون منخفض',
            'message' => $this->product->name . ' — باقي ' . $this->product->stock . ' قطعة فقط',
            'url'     => '/admin/products/' . $this->product->id . '/edit',
        ];
    }
}
