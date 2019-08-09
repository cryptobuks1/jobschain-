<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \JeroenNoten\LaravelAdminLte\Events\BuildingMenu::class => [
            \App\Listeners\AddDynamicAdminMenu::class,
        ],
		Illuminate\Auth\Events\Login::class => [
            App\Listeners\Auth\UserLoggedIn::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            'SocialiteProviders\YouTube\YouTubeExtendSocialite@handle',
            'SocialiteProviders\Twitch\TwitchExtendSocialite@handle',
            'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
			'SocialiteProviders\ThirtySevenSignals\ThirtySevenSignalsExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
