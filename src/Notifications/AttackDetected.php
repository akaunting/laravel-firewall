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
     * Channels with built-in support, which don't require a custom handler.
     */
    const DEFAULT_CHANNELS = [
        'mail',
        'slack'
    ];

    /**
     * The log model.
     *
     * @var object
     */
    public $log;

    /**
     * The notification config.
     */
    public array $notifications;

    /**
     * Create a notification instance.
     *
     * @param  object  $log
     */
    public function __construct($log)
    {
        $this->log = $log;
        $this->notifications = config('firewall.middleware.' . $log->middleware . '.notifications', config('firewall.notifications'));
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

        foreach ($this->notifications as $channel => $settings) {
            if (empty($settings['enabled'])) {
                continue;
            }

            $channel = in_array($channel, self::DEFAULT_CHANNELS) ? $channel : $settings['channel'];

            $channels[] = $channel;
        }

        return $channels;
    }

    /**
     * Get the notification's queues.
     * @return array|string
     */

    public function viaQueues(): array
    {
        $channels = [];

        foreach ($this->notifications as $channel => $settings) {

            $key = in_array($channel, self::DEFAULT_CHANNELS) ? $channel : $settings['channel'];
            
            $channels[$key] = $settings['queue'] ?? 'default';
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
            'domain' => $domain,
        ]);

        $message = trans('firewall::notifications.mail.message', [
            'domain' => $domain,
            'middleware' => ucfirst($this->log->middleware),
            'ip' => $this->log->ip,
            'url' => $this->log->url,
        ]);

        return (new MailMessage)
            ->from($this->notifications['mail']['from'], $this->notifications['mail']['name'])
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
            ->from($this->notifications['slack']['from'], $this->notifications['slack']['emoji'])
            ->to($this->notifications['slack']['channel'])
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
