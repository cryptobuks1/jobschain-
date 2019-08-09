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

use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;
use JsValidator;

class CvsController extends Controller
{
	
	
    /**
     * Display a listing of the resource. (uses ajax table)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
		return view('_admin.cvs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		$this->authorize('create', Cv::class);
		$jsvalidator = JsValidator::make([
			'phones' => 'required|string',
			'address' => 'required|string',
			'qualification' => 'required|string',
			'location' => 'required|nullable',
			'country' => 'required|nullable',
			'description' => 'required|text',
			'expirience' => 'required|string',
			'salary' => 'nullable|string',
			'type' => 'required|in:full_time,part_time,freelance'
		]);
		
        return view('_admin.cvs.create',compact('jsvalidator'));
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
		$this->authorize('create', Cv::class);
        $this->validate($request, [
			'phones' => 'required|string',
			'address' => 'required|string',
			'qualification' => 'required|string',
			'location' => 'required|nullable',
			'country' => 'required|nullable',
			'description' => 'required|text',
			'expirience' => 'required|string',
			'salary' => 'nullable|string',
			'type' => 'required|in:full_time,part_time,freelance'
		]);
        $requestData = $request->all();
        
        Cv::create($requestData);
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
		
       // $cv = Cv::findOrFail($id);
        return view('_admin.cvs.show', compact('cv'));
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
		$this->authorize('update', $cv);
        //$cv = Cv::findOrFail($id);
		//add the jsvalidator
		$jsvalidator = JsValidator::make([
			'phones' => 'required|string',
			'address' => 'required|string',
			'qualification' => 'required|string',
			'location' => 'required|nullable',
			'country' => 'required|nullable',
			'description' => 'required|text',
			'expirience' => 'required|string',
			'salary' => 'nullable|string',
			'type' => 'required|in:full_time,part_time,freelance'
		]);
		
        return view('_admin.cvs.edit', compact('cv', 'jsvalidator'));
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
		$this->authorize('update', $cv);
        $this->validate($request, [
			'phones' => 'required|string',
			'address' => 'required|string',
			'qualification' => 'required|string',
			'location' => 'required|nullable',
			'country' => 'required|nullable',
			'description' => 'required|text',
			'expirience' => 'required|string',
			'salary' => 'nullable|string',
			'type' => 'required|in:full_time,part_time,freelance'
		]);
        $requestData = $request->all();
        
        $cv->update($requestData);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Cv','action'=> __('app.updated')])]);
     
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
		$this->authorize('delete', $cv);
		$cv->delete();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Cv','action'=> __('app.deleted')])]);

    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_status(Request $request, Cv $cv)
    {
		$this->authorize('update', $cv);
        //$cv = Cv::findOrFail($id);
		$cv->status = $cv->status  == 0? 1:0;
		$cv->save();
		$action= $cv->status == 1 ?  __('app.activated'): __('app.deactivated');
		
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>Cv,'action'=> $action ])]);
       
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
		$cvs = Cv::findMany($id);
		foreach ($cvs as $cv ){
			$this->authorize('delete', $cv);
		}
        Cv::destroy($request->ids);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Cv','action'=> __('app.deleted')])]);
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
		$cvs = Cv::findMany($request->ids);
		foreach ($cvs as $cv ){
			$this->authorize('delete', $cv);
		}
        Cv::where('status','!=', 3)->whereIn('id', $request->ids)->update(['status'=>$request->status]);
		$action= $request->status == 1 ?  __('app.activated'): __('app.deactivated');
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Cv','action'=> $action ])]);
    }
	
	/**
     * Get the Table.
     *
     * 
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function table(){
		 $cv = Cv::query();
		return Datatables::of($cv)
			->rawColumns(['id','status','actions'])
			->setRowId(function ($item) {
				return $item->id ;
			})
			->editColumn('id', function ($item) {
				return '<input name="ids[]" class="chkbx" type="checkbox" value="'.$item->id.'"/>';
			})
			->editColumn('status', function ($item) {
				$name = __('app.OFF');
				$label = 'danger';
				if($item->status == 1){
					$name = __('app.ON');;
					$label = 'success';
				}
				return '<a data-table="Cv" class="ajax_link refresh btn btn-sm btn-'.$label.' btn-block" href="'.route('admin.cvs.toggle_status', $item->id).'" data-toggle="tooltip" title="'.__('app.Edit').'">
							<span class="hidden-xs hidden-sm hidden-md">'.$name.'</span>
						 </a>';
	
			})
			->addColumn('actions', function ($item) {
				 return'<a href="'.route('admin.cvs.edit', $item->id) .'" title="'.__('app.edit').' Cv"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> '.__('app.edit').'</button></a>
				 <form data-table="Cv"  method="POST" class="ajax_form refresh" action="'.route('admin.cvs.destroy' , $item->id) .'" accept-charset="UTF-8" style="display:inline">
				 '.method_field("DELETE") .'
				 '.csrf_field() .'
				 <a  data-title="Please Confirm Delete" data-message="Do your really want to Delete this Cv? This Action cannot be reversed" data-toggle="modal" href="#confirmDelete" data-target="#confirmDelete"  class="btn btn-danger btn-sm" title="'.__('app.delete').'Cv" ><i class="fa fa-trash-o" aria-hidden="true"></i> '.__('app.delete').'</a>
		</form>';
			}) ->toJson();
	}
}
