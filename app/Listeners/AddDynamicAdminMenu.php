<?php

namespace App\Listeners;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddDynamicAdminMenu
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
     * @param  BuildingMenu  $event
     * @return void
     */
    public function handle(BuildingMenu $event)
    {
		$event->menu->add(__('admin.main_navigation'));
		$event->menu->add([
			'text'        => __('admin.bashboard'),
			'url'         => 'admin',
			'icon'        => 'home',
			'label'       => 4,
			'label_color' => 'success',
		]);
		$event->menu->add([
			'text'    => __('admin.members'),
			'icon'    => 'users',
			'submenu' => [ 
					[
						'text'        => __('admin.users')	,
						'url'         => 'admin/users',
						'icon'        => 'user'
					],
					[
						'text'        => __('admin.new_user'),
						'url'         => 'admin/users/create',
						'icon'        => 'user'
					],
					
					
					
			]
		]);
	
		$event->menu->add(__('admin.blockchain'));
		
		$event->menu->add([
			'text'    => __('admin.blockchain_admin'),
			'icon'    => 'exchange',
			'submenu' => [

					[
						'text' => __('app.balances'),
						'url'  => route('admin.balances.index'),
						'icon' => 'university',
					],
					[
						'text' => __('app.sent'),
						'url'  => route('admin.etxs.index'),
						'icon' => 'arrow-circle-right',
					],
					[
						'text' => __('app.received'),
						'url'  => route('admin.txs.index'),
						'icon' => 'arrow-circle-left',
					],
					[
						'text' => __('app.deposits'),
						'url'  => route('admin.deposits.index'),
						'icon' => 'dollar',
					],
					[
						'text' => __('app.addresses'),
						'url'  => route('admin.addresses.index'),
						'icon' => 'unlock-alt',
					],
					[
						'text' => __('app.jobs'),
						'url'  => route('admin.jobs.index'),
						'icon' => 'newspaper-o',
					],
					
					[
						'text' => __('app.cvs'),
						'url'  => route('admin.cvs.index'),
						'icon' => 'sticky-note-o',
					],
					
					[
						'text' => __('app.messages'),
						'url'  => route('admin.msgs.index'),
						'icon' => 'envelope',
					],
					[
						'text' => __('admin.wallets')	,
						'url' => '/wallets',//route('admin.wallets.index'),
						'icon' => 'navicon',
					]
			]
		]);
		
	

		
	

		$event->menu->add(__('admin.my_account'));
		$event->menu->add([
						'text' => __('app.settings'),
						'url'  => 'admin/settings',
						'icon' => 'cog',
					]);
		$event->menu->add([
			'text' => __('admin.edit_my_acccount')	,
			'url' => 'admin/users/'.\Auth::user()->id.'/edit',
			'icon' => 'user',
		]);
		
		
		$event->menu->add(__('admin.info'));
		$event->menu->add([
			'text'       => date('D d M Y'),
			'icon_color' => 'red',
		]);
		$event->menu->add([
			'text'       => __('admin.app_name'),
			'icon_color' => 'yellow',
		]);
	}
}
