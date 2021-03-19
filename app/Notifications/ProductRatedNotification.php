<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;
use App\Models\Product;

class ProductRatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    //public $connection = 'redis';// notification conection Queue

    private User $qualifier;
    private Product $product;
    private float $score;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Product $product, User $qualifier, float $score)
    {
        $this->product = $product;
        $this->qualifier = $qualifier;
        $this->score = $score;
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
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            'mail' => 'mail-queue',
            //'slack' => 'slack-queue',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail_context = [
            'product' => $this->product,
            'qualifier' => $this->qualifier,
            'score' => $this->score,
        ];

        return (new MailMessage)
            ->view(
                'emails.product_rate_html', //['emails.ping_html', 'emails.ping_plain'],
                $mail_context
            )
            ->subject('Your Product was Rated by an User');

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
