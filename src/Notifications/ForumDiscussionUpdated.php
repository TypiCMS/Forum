<?php

namespace TypiCMS\Modules\Forum\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForumDiscussionUpdated extends Notification
{
    use Queueable;

    protected $discussion;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($discussion)
    {
        $this->discussion = $discussion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(trans('Just wanted to let you know that someone has responded to a forum post.'))
            ->action(trans('View the discussion.'), route('forum.discussion.showInCategory', [$this->discussion->category->slug, $this->discussion->slug]))
            ->line(trans('If you no longer wish to be notified when someone responds to this form post be sure to uncheck the notification setting at the bottom of the discussion page.'))
            ->line(trans('Have a great day!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
