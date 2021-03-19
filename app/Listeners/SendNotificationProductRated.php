<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\EntityRatedEvent;
use App\Models\Product;

use App\Notifications\ProductRatedNotification;

class SendNotificationProductRated implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EntityRatedEvent $event)
    {
        $rateable = $event->getRateable();

        if ($rateable instanceof Product){
            $notification = new ProductRatedNotification(
                $rateable, $event->getUser(), $event->getScore()
            );

            $rateable->createdBy->notify($notification);
        }
    }
}
