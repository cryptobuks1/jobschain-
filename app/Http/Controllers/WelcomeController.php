<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Cv;
class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
		$jobs = Job::take(30)->get();
		$cvs = Cv::take(30)->get();
		$categories = [];
		foreach(config('categories') as $cat){
			$obj = (object)[];
			$obj->name = $cat;
			$obj->count =$jobs->where('category',$cat)->count();
			$categories[] = $obj;
		}
        return view('welcome', compact('jobs','cvs','categories'));
    }
	 public function privacy()
    {
        return view('privacy');
    }
	public function qrcode(Request $request) 
	{
		return  \QRCode::text($request->text)->png();   
	}
	
}
