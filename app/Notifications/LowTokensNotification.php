<?php

namespace App\Notifications;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowTokensNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    public function __construct(UserSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $percentage = ($this->subscription->tokens_remaining / $this->subscription->plan->tokens_limit) * 100;
        
        return (new MailMessage)
            ->subject('⚠️ تنبيه: التوكنات المتبقية منخفضة')
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('نود إعلامك بأن التوكنات المتبقية في اشتراكك أصبحت منخفضة.')
            ->line('**التوكنات المتبقية:** ' . number_format($this->subscription->tokens_remaining) . ' توكن')
            ->line('**النسبة المتبقية:** ' . number_format($percentage, 1) . '%')
            ->line('**الباقة الحالية:** ' . $this->subscription->plan->name)
            ->action('ترقية الباقة', url('/pricing'))
            ->line('لتجنب انقطاع الخدمة، يُرجى ترقية باقتك أو الانتظار حتى إعادة تعيين التوكنات الشهرية.')
            ->salutation('مع تحيات فريق CulturalTranslate');
    }

    public function toArray($notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'tokens_remaining' => $this->subscription->tokens_remaining,
            'tokens_limit' => $this->subscription->plan->tokens_limit,
            'plan_name' => $this->subscription->plan->name,
            'message' => 'التوكنات المتبقية أقل من 20%',
        ];
    }
}
