<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use NotificationChannels\Infobip\InfobipChannel;
use NotificationChannels\Infobip\InfobipMessage;


class TwoFa extends Notification
{
    use Queueable;


    protected $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        //
		$this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
		$via = ['mail'];
		if(!empty($notifiable->phone_number)){
			$via[] = InfobipChannel::class;
		}
		return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage();
        $message
            ->from(config('laravel2step.verificationEmailFrom'), config('laravel2step.verificationEmailFromName'))
            ->subject(trans('laravel2step::laravel-verification.verificationEmailSubject'))
            ->greeting(trans('laravel2step::laravel-verification.verificationEmailGreeting', ['username' => $notifiable->name]))
            ->line(trans('laravel2step::laravel-verification.verificationEmailMessage'))
            ->line($this->code)
            ->action(trans('laravel2step::laravel-verification.verificationEmailButton'), route('users.authentication'));

        return $message;
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
	
	public function toNexmo($notifiable)
	{
		return (new NexmoMessage)
					->content(__('sms.twoFa',['code' => $this->code,'site'=>env('APP_NAME')]));
	}
	
	public function toInfobip($notifiable)
    {
        return (new InfobipMessage())
            ->content(__('sms.twoFa',['code' => $this->code,'site'=>env('APP_NAME')]));
    }
	
}
