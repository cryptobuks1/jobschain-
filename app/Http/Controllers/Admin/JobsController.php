<?php
/**Envatic Crypto AP
* Adapted by
 *Stephen Isaac:  ofuzak@gmail.com>.
 *Skype: ofuzak
 *www.evatic.com (Deploy Scripts , Apps , coins in One click)
 */
	
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;
use JsValidator;

class JobsController extends Controller
{
	
	
    /**
     * Display a listing of the resource. (uses ajax table)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
		return view('_admin.jobs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		$this->authorize('create', Job::class);
		$jsvalidator = JsValidator::make([
			'country_id' => 'required|numeric|exists:countries,id',
			'title' => 'required|string',
			'company_name' => 'required|string',
			'qualification' => 'required|string',
			'count' => 'required|numeric',
			'description' => 'required|string',
			'expirience' => 'required|numeric'
		]);
		
        return view('_admin.jobs.create',compact('jsvalidator'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
		$this->authorize('create', Job::class);
        $this->validate($request, [
			'country_id' => 'required|numeric|exists:countries,id',
			'title' => 'required|string',
			'company_name' => 'required|string',
			'qualification' => 'required|string',
			'count' => 'required|numeric',
			'description' => 'required|string',
			'expirience' => 'required|numeric'
		]);
        $requestData = $request->all();
        Job::create($requestData);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.added')])]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Job $job)
    {
		
       // $job = Job::findOrFail($id);
        return view('_admin.jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Job $job)
    {
		$this->authorize('update', $job);
        //$job = Job::findOrFail($id);
		//add the jsvalidator
		$jsvalidator = JsValidator::make([
			'country_id' => 'required|numeric|exists:countries,id',
			'title' => 'required|string',
			'company_name' => 'required|string',
			'qualification' => 'required|string',
			'count' => 'required|numeric',
			'description' => 'required|string',
			'expirience' => 'required|numeric'
		]);
		
        return view('_admin.jobs.edit', compact('job', 'jsvalidator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Job $job)
    {
		$this->authorize('update', $job);
        $this->validate($request, [
			'country_id' => 'required|numeric|exists:countries,id',
			'title' => 'required|string',
			'company_name' => 'required|string',
			'qualification' => 'required|string',
			'count' => 'required|numeric',
			'description' => 'required|string',
			'expirience' => 'required|numeric'
		]);
        $requestData = $request->all();
        
        $job->update($requestData);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.updated')])]);
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Job $job)
    {
		$this->authorize('delete', $job);
		$job->delete();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.deleted')])]);

    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_status(Request $request, Job $job)
    {
		$this->authorize('update', $job);
        //$job = Job::findOrFail($id);
		$job->status = $job->status  == 0? 1:0;
		$job->save();
		$action= $job->status == 1 ?  __('app.activated'): __('app.deactivated');
		
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>Job,'action'=> $action ])]);
       
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
		
		if(!count($request->ids))
		return response()->json(['status' => 'SUCCESS','message' => __('app.nothing_selected')]);
		$jobs = Job::findMany($id);
		foreach ($jobs as $job ){
			$this->authorize('delete', $job);
		}
        Job::destroy($request->ids);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.deleted')])]);
    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_statuses(Request $request)
    {
		if(!count($request->ids))
		return response()->json(['status' => 'SUCCESS','message' => __('app.nothing_selected')]);
		$jobs = Job::findMany($request->ids);
		foreach ($jobs as $job ){
			$this->authorize('delete', $job);
		}
        Job::where('status','!=', 3)->whereIn('id', $request->ids)->update(['status'=>$request->status]);
		$action= $request->status == 1 ?  __('app.activated'): __('app.deactivated');
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> $action ])]);
    }
	
	/**
     * Get the Table.
     *
     * 
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function table(){
		 $job = Job::query();
		return Datatables::of($job)
			->rawColumns(['id','status','actions'])
			->setRowId(function ($item) {
				return $item->id ;
			})
			->editColumn('id', function ($item) {
				return '<input name="ids[]" class="chkbx" type="checkbox" value="'.$item->id.'"/>';
			})
			->addColumn('actions', function ($item) {
				 return'<a href="'.route('admin.jobs.edit', $item->id) .'" title="'.__('app.edit').' Job"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '.__('app.edit').'</button></a>
				 <form data-table="Job"  method="POST" class="ajax_form refresh" action="'.route('admin.jobs.destroy' , $item->id) .'" accept-charset="UTF-8" style="display:inline">
				 '.method_field("DELETE") .'
				 '.csrf_field() .'
				 <a  data-title="Please Confirm Delete" data-message="Do your really want to Delete this Job? This Action cannot be reversed" data-toggle="modal" href="#confirmDelete" data-target="#confirmDelete"  class="btn btn-danger btn-sm" title="'.__('app.delete').'Job" ><i class="fa fa-trash-o" aria-hidden="true"></i> '.__('app.delete').'</a>
		</form>';
			}) ->toJson();
	}
}
