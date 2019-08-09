<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ObserverMakeCommand;

class CrudObserverCommand extends ObserverMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:observer 
							{name : The name of the Crud.}
                            {--model= : Name of the Model.}';

	
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/observer.stub'
        : __DIR__ . '/../stubs/observer.stub';
    }

    
}
