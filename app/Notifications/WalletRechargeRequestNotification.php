<?php

namespace App\Notifications;

use App\Models\WalletRechargeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WalletRechargeRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public WalletRechargeRequest $rechargeRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $user = $this->rechargeRequest->user;

        return [
            'type'    => 'wallet_recharge_request',
            'icon'    => '💳',
            'title'   => 'طلب شحن رصيد جديد',
            'message' => "{$user->name} يطلب شحن رصيد بقيمة \$" . number_format($this->rechargeRequest->amount, 2),
            'url'     => '/admin/wallet/users/' . $user->id . '/transactions',
        ];
    }
}
