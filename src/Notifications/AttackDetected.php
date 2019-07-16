<?php

namespace Akaunting\Firewall\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class AttackDetected extends Notification
{
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
        return ['mail', 'slack'];
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
        $subject = str_replace(':domain', $domain, config('firewall.notifications.mail.subject'));
        $message = str_replace(':domain', $domain, config('firewall.notifications.mail.message'));
        $message = str_replace(':middleware', ucfirst($this->log->middleware), $message);
        $message = str_replace(':ip', $this->log->ip, $message);
        $message = str_replace(':url', $this->log->url, $message);

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
        $domain = request()->getHttpHost();
        $message = str_replace(':domain', $domain, config('firewall.notifications.slack.message'));

        return (new SlackMessage)
            ->error()
            ->from(config('firewall.notifications.slack.from'), config('firewall.notifications.slack.emoji'))
            ->to(config('firewall.notifications.slack.to'))
            ->content($message)
            ->attachment(function ($attachment) {
                $attachment->title($this->log->ip)
                    ->fields([
                        'IP' => $this->log->ip,
                        'Type' => ucfirst($this->log->middleware),
                        'User ID' => $this->log->user_id,
                        'URL' => $this->log->url,
                    ]);
            });
    }
}
