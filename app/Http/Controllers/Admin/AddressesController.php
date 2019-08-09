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

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;
use JsValidator;

class AddressesController extends Controller
{
	
	
    /**
     * Display a listing of the resource. (uses ajax table)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
		return view('_admin.addresses.index');
    }

  

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Address $address)
    {
		$this->authorize('delete', $address);
		$address->delete();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Address','action'=> __('app.deleted')])]);

    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_status(Request $request, Address $address)
    {
		$this->authorize('update', $address);
        //$address = Address::findOrFail($id);
		$address->status = $address->status  == 0? 1:0;
		$address->save();
		$action= $address->status == 1 ?  __('app.activated'): __('app.deactivated');
		
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>Address,'action'=> $action ])]);
       
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
		$addresses = Address::findMany($id);
		foreach ($addresses as $address ){
			$this->authorize('delete', $address);
		}
        Address::destroy($request->ids);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Address','action'=> __('app.deleted')])]);
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
		$addresses = Address::findMany($request->ids);
		foreach ($addresses as $address ){
			$this->authorize('delete', $address);
		}
        Address::where('status','!=', 3)->whereIn('id', $request->ids)->update(['status'=>$request->status]);
		$action= $request->status == 1 ?  __('app.activated'): __('app.deactivated');
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Address','action'=> $action ])]);
    }
	
	/**
     * Get the Table.
     *
     * 
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function table(){
		 $address = Address::query();
		return Datatables::of($address)
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
				return '<a data-table="Address" class="ajax_link refresh btn btn-sm btn-'.$label.' btn-block" href="'.route('admin.addresses.toggle_status', $item->id).'" data-toggle="tooltip" title="'.__('app.Edit').'">
							<span class="hidden-xs hidden-sm hidden-md">'.$name.'</span>
						 </a>';
	
			})
			->addColumn('actions', function ($item) {
				 return'
				 <form data-table="Address"  method="POST" class="ajax_form refresh" action="'.route('admin.addresses.destroy' , $item->id) .'" accept-charset="UTF-8" style="display:inline">
				 '.method_field("DELETE") .'
				 '.csrf_field() .'
				 <a  data-title="Please Confirm Delete" data-message="Do your really want to Delete this Address? This Action cannot be reversed" data-toggle="modal" href="#confirmDelete" data-target="#confirmDelete"  class="btn btn-danger btn-sm" title="'.__('app.delete').'Address" ><i class="fa fa-trash-o" aria-hidden="true"></i> '.__('app.delete').'</a>
				</form>';
			}) ->toJson();
	}
}
