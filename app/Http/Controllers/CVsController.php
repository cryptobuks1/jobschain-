<?php
/**Envatic Crypto AP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
	
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;


class CvsController extends Controller
{
      public function __construct()
      {
        $this->middleware('auth')->only(['create','store']);
        parent::__construct();
      }
  
    /**
     * Display a listing of the resource. (uses ajax table)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
      $cvs = Cv::paginate(3);
		return view('cvs.index',compact('cvs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
       return view('cvs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, Cv $cv)
    {
  
      $cv->address = $request->address;
      $cv->description=$request->description;	
      $cv->location=$request->location;
      $cv->salary=$request->salary;

      $cv->save();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Cv','action'=> __('app.added')])]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Cv $cv)
    {
		
       // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Cv $cv)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Cv $cv)
    {
		//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Cv $cv)
    {
		//
    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_status(Request $request, Cv $cv)
    {
		//
    }
	
	/**
     * Remove the specified resources from storage.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request)
    {
		
		//
    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_statuses(Request $request)
    {
		//
    }
	
	/**
     * Get the Table.
     *
     * 
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

}
