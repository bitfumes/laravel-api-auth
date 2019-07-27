<?php

namespace Bitfumes\ApiAuth\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    private static $toMailCallback;

    /**
     * Get the notification's channels.
     *
     * @return array|string
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage())
            ->subject(Lang::getFromJson('Verify Email Address'))
            ->line(Lang::getFromJson('Please click the button below to verify your email address.'))
            ->action(
                Lang::getFromJson('Verify Email Address'),
                $this->verificationUrl($notifiable)
            )
            ->line(Lang::getFromJson('If you did not create an account, no further action is required.'));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $front_url   = app()['config']['api-auth.front_url'];
        $verify_url  = app()['config']['api-auth.verify_url'];
        $sign        = str_random(40);
        Cache::put("verify-{$notifiable->id}", $sign, Carbon::now()->addMinute(5));
        return "{$front_url}/{$verify_url}/?user={$notifiable->getKey()}&signature={$sign}";
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
