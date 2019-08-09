<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Notifications\Messages\MailMessage;
//use Illuminate\Notifications\Messages\NexmoMessage;
//use NotificationChannels\Infobip\InfobipChannel;
//use NotificationChannels\Infobip\InfobipMessage;
use App\Models\Tx;
use App\Http\Resources\ApiTx;
use Illuminate\Notifications\Messages\BroadcastMessage;

class Deposit extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $tx;

    public function __construct(Tx $tx)
    {
        $this->tx = $tx;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable=null)
    {
		 return (new ApiTx($this->tx))->toArray(request());
    }
	
	
	public function toBroadcast($notifiable)
	{
		return new BroadcastMessage($this->toArray($notifiable));
	}
	
	
}
