<?php
namespace App\Console\Commands;
use Illuminate\Foundation\Console\ResourceMakeCommand;
class CrudResourceCommand extends ResourceMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
	protected $signature = 'crud:resource
                            {name : The name of the transformer.}
							{--resource-array= : The names of the Transformed array.}
							{--collection= : create a collection.}';

   


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.path').'/resource.stub'; 
    }

   

   

   /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
		$transformArray = $this->option("resource-array");
        return $this->replaceTransformArray($stub, $transformArray)->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
	
	
	
	
	protected function replaceTransformArray(&$stub, $transformArray)
    {
        $stub = str_replace('{{transformArray}}', $transformArray, $stub);

        return $this;
    }
	
	
}
