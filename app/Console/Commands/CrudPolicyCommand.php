<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\PolicyMakeCommand;

class CrudPolicyCommand extends PolicyMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:policy 
							{name : The name of the Crud.}
                            {--model= : Name of the Model.}';

	
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/policy.stub'
        : __DIR__ . '/../stubs/policy.stub';
    }

    
}
