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

use App\Models\Etx;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;
use JsValidator;

class EtxsController extends Controller
{
	
	
    /**
     * Display a listing of the resource. (uses ajax table)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
		return view('_admin.etxs.index');
    }

  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Etx $etx)
    {
		$this->authorize('delete', $etx);
		$etx->delete();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Etx','action'=> __('app.deleted')])]);

    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_status(Request $request, Etx $etx)
    {
		$this->authorize('update', $etx);
        //$etx = Etx::findOrFail($id);
		$etx->status = $etx->status  == 0? 1:0;
		$etx->save();
		$action= $etx->status == 1 ?  __('app.activated'): __('app.deactivated');
		
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>Etx,'action'=> $action ])]);
       
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
		$etxs = Etx::findMany($id);
		foreach ($etxs as $etx ){
			$this->authorize('delete', $etx);
		}
        Etx::destroy($request->ids);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Etx','action'=> __('app.deleted')])]);
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
		$etxs = Etx::findMany($request->ids);
		foreach ($etxs as $etx ){
			$this->authorize('delete', $etx);
		}
        Etx::where('status','!=', 3)->whereIn('id', $request->ids)->update(['status'=>$request->status]);
		$action= $request->status == 1 ?  __('app.activated'): __('app.deactivated');
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Etx','action'=> $action ])]);
    }
	
	/**
     * Get the Table.
     *
     * 
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function table(){
		 $etx = Etx::query();
		return Datatables::of($etx)
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
				return '<a data-table="Etx" class="ajax_link refresh btn btn-sm btn-'.$label.' btn-block" href="'.route('admin.etxs.toggle_status', $item->id).'" data-toggle="tooltip" title="'.__('app.Edit').'">
							<span class="hidden-xs hidden-sm hidden-md">'.$name.'</span>
						 </a>';
	
			})
			->addColumn('actions', function ($item) {
				 return'
				 <form data-table="Etx"  method="POST" class="ajax_form refresh" action="'.route('admin.etxs.destroy' , $item->id) .'" accept-charset="UTF-8" style="display:inline">
				 '.method_field("DELETE") .'
				 '.csrf_field() .'
				 <a  data-title="Please Confirm Delete" data-message="Do your really want to Delete this Etx? This Action cannot be reversed" data-toggle="modal" href="#confirmDelete" data-target="#confirmDelete"  class="btn btn-danger btn-sm" title="'.__('app.delete').'Etx" ><i class="fa fa-trash-o" aria-hidden="true"></i> '.__('app.delete').'</a>
		</form>';
			}) ->toJson();
	}
}
