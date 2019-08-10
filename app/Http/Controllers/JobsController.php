<?php

namespace App\Http\Controllers;
use App\Models\Job;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->only(['create','store']);
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        $jobs= Job::paginate(10);
        return view('jobs.index',compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Job $job)
    {
        
        $job->country = $request->country;
        $job->address = $request->address;
        $job->company_name = $request->company_name;
        $job->title = $request->title;
        $job->salary = $request->salary;
        $job->qualifications = $request->qualifications;
        $job->description = $request->description;
        $job->category = $request->category;
        $job->expirience = $request->expirience;
        $job->expiry = $request->expiry;
        $job->country = $request->country;
        $job->save();
        //$requestData = $request->all();
        //Job::create($requestData);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.added')])]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $job = Job::findOrFail($id);
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
		
        return view('jobs.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $job->update($requestData);
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.updated')])]);
  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', $job);
		$job->delete();
		return response()->json(['status' => 'SUCCESS','message' => __('app.action_ok',['item'=>'Job','action'=> __('app.deleted')])]);

    }
}
