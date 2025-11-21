<?php

namespace App\Notifications;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class SubscriptionExpiryNotification extends Notification implements ShouldQueue
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
        $daysRemaining = Carbon::now()->diffInDays($this->subscription->expires_at, false);
        
        return (new MailMessage)
            ->subject('⏰ تنبيه: اشتراكك على وشك الانتهاء')
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('نود إعلامك بأن اشتراكك في CulturalTranslate على وشك الانتهاء.')
            ->line('**الباقة الحالية:** ' . $this->subscription->plan->name)
            ->line('**تاريخ الانتهاء:** ' . $this->subscription->expires_at->format('Y-m-d'))
            ->line('**الأيام المتبقية:** ' . $daysRemaining . ' يوم')
            ->line('**التوكنات المتبقية:** ' . number_format($this->subscription->tokens_remaining) . ' توكن')
            ->action('تجديد الاشتراك', url('/pricing'))
            ->line('لضمان استمرار الخدمة دون انقطاع، يُرجى تجديد اشتراكك قبل تاريخ الانتهاء.')
            ->salutation('مع تحيات فريق CulturalTranslate');
    }

    public function toArray($notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'expires_at' => $this->subscription->expires_at->toDateTimeString(),
            'days_remaining' => Carbon::now()->diffInDays($this->subscription->expires_at, false),
            'plan_name' => $this->subscription->plan->name,
            'message' => 'اشتراكك سينتهي خلال 7 أيام',
        ];
    }
}
