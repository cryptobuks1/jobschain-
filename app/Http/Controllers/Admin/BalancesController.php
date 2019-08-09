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

use App\Models\Balance;
use App\Models\Deposit;
use Bitwasp\Bitcoin\Amount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Yajra\Datatables\Datatables;
use JsValidator;

class BalancesController extends Controller
{
	
	public function __construct(){
		$this->middleware('pass', ['only' => ['update']]);
		parent::__construct();
	}
	
    /**
     * Display a listing of the resource. (uses ajax table)
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
		return view('_admin.balances.index');
    }


    /**
     * Displays the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Balance $balance)
    {
		
       // $balance = Balance::findOrFail($id);
        return view('_admin.balances.show', compact('balance'));
    }

    /**
	 * Top up a users Balance
     * Updates the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,  Balance $balance)
    {
		$this->authorize('update', $balance);
        $this->validate($request, [
			'amount' => 'required|numeric',
			'balance_id' => 'required|numeric|exists:balances,id',
		]);
		$crypto = new Amount;
		$deposit = new Deposit;
		$deposit->user_id = $balance->user_id; 
		$deposit->amount = $request->amount;  
		$deposit->coins = $crypto->toSatoshi($request->coins);  
		$deposit->value = null;  
		$deposit->currency = $balance->symbol; 
		$deposit->gateway = 'Top Up';  
		$deposit->txid = Str::random(18);  
		$deposit->status = 'complete';  
		$deposit->active = true; 
		$deposit->save(); 
		$balance ->balance += $request->amount;
		$balance->save();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Balance','action'=> __('app.updated')])]);
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, Balance $balance)
    {
		$this->authorize('delete', $balance);
		$balance->delete();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Balance','action'=> __('app.deleted')])]);

    }
	
	  /**toggle Item status.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggle_status(Request $request, $id)
    {
		$balance = Balance::findOrFail($id);
		$this->authorize('update', $balance);
		$balance->status = $balance->status  == 0? 1:0;
		$balance->save();
		$action= $balance->status == 1 ?  __('app.activated'): __('app.deactivated');
		
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Balance','action'=> $action ])]);
       
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
		$balances = Balance::findMany($id);
		foreach ($balances as $balance ){
			$this->authorize('delete', $balance);
		}
        Balance::destroy($request->ids);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Balance','action'=> __('app.deleted')])]);
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
		$balances = Balance::findMany($request->ids);
		foreach ($balances as $balance ){
			$this->authorize('delete', $balance);
		}
        Balance::where('status','!=', 3)->whereIn('id', $request->ids)->update(['status'=>$request->status]);
		$action= $request->status == 1 ?  __('app.activated'): __('app.deactivated');
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Balance','action'=> $action ])]);
    }
	
	/**
     * Get the Table.
     *
     * 
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
	 public function table(){
		 $balance = Balance::with(['user'])->get();
		return Datatables::of($balance)
			->rawColumns(['symbol','id','status','actions'])
			->setRowId(function ($item) {
				return $item->id ;
			})
			->editColumn('user_id', function ($item) {
				return $item->user->name .' | '.$item->user->email;
			})
			->editColumn('id', function ($item) {
				return '<input name="ids[]" class="chkbx" type="checkbox" value="'.$item->id.'"/>';
			})
			->editColumn('balance', function ($item) {
				return $item->balance.$item->symbol;
			})
			->editColumn('symbol', function ($item) {
				return '<a class="btn btn-sm btn-success btn-block" data-toggle="modal" href="#topupModal-'.$item->id.'" data-toggle="tooltip" title="'.__('app.topup').'">
							<span class="hidden-xs hidden-sm hidden-md">'.__('app.topup').'</span>
						 </a><div class="modal fade" id="topupModal-'.$item->id.'" style="display: none;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h3 class="modal-title">Top Up '.$item->user->name .' | '.$item->user->email.'</h3>
              </div>
			  <form method="post" action="'.route('admin.balances.update',$item->id).'" class="ajax_form ">
			  <input type="hidden" name="_method" value="PUT">
			  <input type="hidden" name="balance_id" value="'.$item->id.'">
			  <input type="hidden" name="_token" value="'.csrf_token().'">
              <div class="modal-body">
			  	<div style="width:100%" class="form-group row">
					<div class="col-md-4">
					    <label class="form-label">Amount</label>
						<input id="tp-'.$item->id.'" type="number" name="amount" class="form-control" placeholder="Amount">
					</div>
					<div class="col-md-4">
						<label class="form-label">Password</label>
						<input  type="password" name="password" class="form-control" placeholder="Enter Password">
					</div>
					<label class="form-label">&nbsp;</label>
					<div class="col-md-4">Current: '.$item->balance.$item->symbol.'</div>
				</div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button  type="submit" onclick="$(\'#topupModal-'.$item->id.'\').modal(\'hide\')" class="btn btn-primary">Save changes</button>
              </div>
			  </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>';
	
			})
			->editColumn('status', function ($item) {
				$name = __('app.OFF');
				$label = 'danger';
				if($item->status == 1){
					$name = __('app.ON');;
					$label = 'success';
				}
				return '<a data-table="Balance" class="ajax_link refresh btn btn-sm btn-'.$label.' btn-block" href="'.route('admin.balances.toggle_status', $item->id).'" data-toggle="tooltip" title="'.__('app.Edit').'">
							<span class="hidden-xs hidden-sm hidden-md">'.$name.'</span>
						 </a>';
	
			})
			->addColumn('actions', function ($item) {
				 return'
				 <form data-table="Balance"  method="POST" class="ajax_form refresh" action="'.route('admin.balances.destroy' , $item->id) .'" accept-charset="UTF-8" style="display:inline">
				 '.method_field("DELETE") .'
				 '.csrf_field() .'
				 <a  data-title="Please Confirm Delete" data-message="Do your really want to Delete this Balance? This Action cannot be reversed" data-toggle="modal" href="#confirmDelete" data-target="#confirmDelete"  class="btn btn-danger btn-sm" title="'.__('app.delete').'Balance" ><i class="fa fa-trash-o" aria-hidden="true"></i> '.__('app.delete').'</a>
		</form>';
			}) ->toJson();
	}
}
