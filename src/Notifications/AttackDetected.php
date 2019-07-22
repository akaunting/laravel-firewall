<?php

namespace Akaunting\Firewall\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class AttackDetected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The log model.
     *
     * @var object
     */
    public $log;

    /**
     * Create a notification instance.
     *
     * @param  object  $log
     */
    public function __construct($log)
    {
        $this->log = $log;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        $channels = [];

        foreach (config('firewall.notifications') as $channel => $settings) {
            if (!$settings['enabled']) {
                continue;
            }

            $channels[] = $channel;
        }

        return $channels;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $domain = request()->getHttpHost();

        $subject = trans('firewall::notifications.mail.subject', [
            'domain' => $domain
        ]);

        $message = trans('firewall::notifications.mail.message', [
            'domain' => $domain,
            'middleware' => ucfirst($this->log->middleware),
            'ip' => $this->log->ip,
            'url' => $this->log->url,
        ]);

        return (new MailMessage)
            ->from(config('firewall.notifications.mail.from'), config('firewall.notifications.mail.name'))
            ->subject($subject)
            ->line($message);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $message = trans('firewall::notifications.slack.message', [
            'domain' => request()->getHttpHost(),
        ]);

        return (new SlackMessage)
            ->error()
            ->from(config('firewall.notifications.slack.from'), config('firewall.notifications.slack.emoji'))
            ->to(config('firewall.notifications.slack.to'))
            ->content($message)
            ->attachment(function ($attachment) {
                $attachment->fields([
                    'IP' => $this->log->ip,
                    'Type' => ucfirst($this->log->middleware),
                    'User ID' => $this->log->user_id,
                    'URL' => $this->log->url,
                ]);
            });
    }
}
