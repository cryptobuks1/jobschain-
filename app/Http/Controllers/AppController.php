<?php

namespace App\Http\Controllers;

class AppController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome');
    }
	
	public function home()
    {
        return view('user.wallets');
    }
	
	
	
	public function authentication()
    {
        return view('user.authentication');
    }
	public function profile()
    {
        return view('user.profile');
    }
	
	//admin
	public function wallet()
    {
        return view('admin.wallets');
    }
	public function balances()
    {
        return view('admin.balances');
    }
	
	public function txs()
    {
        return view('admin.txs');
    }
	public function etxs()
    {
        return view('admin.etxs');
    }
	
	public function adminWallet()
    {
        return view('admin.wallets');
    }
	
	public function users()
    {
        return view('admin.users');
    }
	
	public function redeems()
    {
        return view('admin.redeems');
    }
	
	public function classics()
    {
        return view('admin.classics');
    }
}
