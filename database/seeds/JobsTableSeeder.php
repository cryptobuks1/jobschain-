<?php

use App\Models\Job;
use Illuminate\Database\Seeder;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		
            Job::create([
				'user_id'=>1, 
				'country_id'=>4, 
				'country'=>'Germany', 
				'address'=>'CLmMPQjncKFPyaK8MVCDXnJVDB1LEqUdH5', 'publickey'=>'027cff3590f016f67388d9db99e0e3b4b54b4ec95c09766b5745409a1151c17bfd', 
				'company_name'=>'Envatic Edge', 
				'category'=>'Engineering', 
				'title'=>'Software Engineer', 
				'salary'=>'80,000-120,000$', 
				'qualifications'=>'Master in Engineering', 
				'description'=>'Determines operational feasibility by evaluating analysis, problem definition, requirements, solution development, and proposed solutions', 
				'category'=>'Engineering', 
				'expiry'=>now()->addDays(2), 
				'expirience'=>2, 
				'count'=>2, 
				'status'=>'open', 
				'active'=>1,
            ]);
		
         
	}
}
