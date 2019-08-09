<?php

namespace App\Console\Commands;
use Appzcoder\CrudGenerator\Commands\CrudControllerCommand as cmdgen;
use Illuminate\Support\Str;
class CrudControllerCommand extends cmdgen
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:jcontroller
                            {name : The name of the controler.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--model-namespace= : The namespace of the Model.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--view-path= : The name of the view path.}
                            {--fields= : Field names for the form & migration.}
                            {--validations= : Validation rules for the fields.}
                            {--route-group= : Prefix of the route group.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--force : Overwrite already existing controller.}
							{--api= : use api controller.}';

    
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $viewPath = $this->option('view-path') ? $this->option('view-path') . '.' : '';
        $crudName = strtolower($this->option('crud-name'));
        $crudNameSingular = str_singular($crudName);
        $modelName = $this->option('model-name');
        $modelNamespace = $this->option('model-namespace');
        $routeGroup = ($this->option('route-group')) ? $this->option('route-group') . '/' : '';
        $routePrefix = ($this->option('route-group')) ? $this->option('route-group') : '';
        $routePrefixCap = ucfirst($routePrefix);
        $perPage = intval($this->option('pagination'));
        $viewName = snake_case($this->option('crud-name'), '-');
        $fields = $this->option('fields');
        $validations = rtrim($this->option('validations'), ';');
        $validationRules = '';
		$jsvalidation = '';
        if (trim($validations) != '') {
            $validationRules = "\$this->validate(\$request, [";
            $rules = explode(';', $validations);
			$xrules ='';
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }

                // extract field name and args
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $xrules .= "\n\t\t\t'$fieldName' => '$rules',";
            }

            $validationRules .= substr($xrules, 0, -1); // lose the last comma
            $validationRules .= "\n\t\t]);";
			$jsvalidation    = "\$jsvalidator = JsValidator::make([";
            $jsvalidation .= substr($xrules, 0, -1); // lose the last comma again
            $jsvalidation .= "\n\t\t]);";
        }
		
		

        if (\App::VERSION() < '5.3') {
            $snippet = <<<EOD
        if (\$request->hasFile('{{fieldName}}')) {
            \$file = \$request->file('{{fieldName}}');
            \$fileName = str_random(40) . '.' . \$file->getClientOriginalExtension();
            \$destinationPath = storage_path('/app/public/uploads');
            \$file->move(\$destinationPath, \$fileName);
            \$requestData['{{fieldName}}'] = 'uploads/' . \$fileName;
        }
EOD;
        } else {
            $snippet = <<<EOD
        if (\$request->hasFile('{{fieldName}}')) {
            \$requestData['{{fieldName}}'] = \$request->file('{{fieldName}}')
                ->store('uploads', 'public');
        }
EOD;
        }


        $fieldsArray = explode(';', $fields);
        $fileSnippet = '';
        $whereSnippet = '';
		$relatedModels="" ;
		$relatedModelsItems="" ;
		$ajaxData='';
        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $index => $item) {
                $itemArray = explode('#', $item);
				// remove migration modifiers string:16|nullable , 
				if(Str::contains( $itemArray[1],':')||Str::contains( $itemArray[1],'|')){
					$types = explode('|', str_replace(':', "|", $itemArray[1]));
					$type = $types[0];
				}else{
					$type = $itemArray[1];
				}
				if (trim($type ) != 'select' && isset($itemArray[3])) {
					$relmod = str_replace("options=","",$itemArray[3] );
					$relatedModels.="
		$".$relmod."s = \\App\\".$modelNamespace.ucfirst($relmod)."::all();";
					$ajaxData.="
			'".$relmod."s' => $".$relmod."s->transformWith(new ".ucfirst($relmod)."sTransformer())->toArray(),";
					$relatedModelsItems .=",'".$relmod."s'";
				}

				
                if (trim($type ) == 'file') {
                    $fileSnippet .= str_replace('{{fieldName}}', trim($itemArray[0]), $snippet) . "\n";
                }

                $fieldName = trim($itemArray[0]);

                $whereSnippet .= ($index == 0) ? "where('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                " : "->orWhere('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                ";
            }
            $whereSnippet .= "->";
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceViewPath($stub, $viewPath)
            ->replaceViewName($stub, $viewName)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceModelNamespaceSegments($stub, $modelNamespace)
            ->replaceRouteGroup($stub, $routeGroup)
            ->replaceRoutePrefix($stub, $routePrefix)
            ->replaceRoutePrefixCap($stub, $routePrefixCap)
            ->replaceValidationRules($stub, $validationRules)
			->replaceJSValidation($stub, $jsvalidation)
			->replaceRelatedModels($stub, $relatedModels, $relatedModelsItems)
            ->replacePaginationNumber($stub, $perPage)
            ->replaceFileSnippet($stub, $fileSnippet)
            ->replaceWhereSnippet($stub, $whereSnippet)
			->replaceAjaxData($stub, $ajaxData)
            ->replaceClass($stub, $name);
    }
	
	protected function getStub()
    {
		$api = $this->option('api');
		if(!$api) return parent::getStub();
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/api-controller.stub'
        : __DIR__ . '/../stubs/api-controller.stub';
    }
	
	/**
     * Replace the javascript validationRules for the given stub.
     *
     * @param  string  $stub
     * @param  string  $validationRules
     *
     * @return $this
     */
    protected function replaceJSValidation(&$stub, $jsvalidation)
    {
        $stub = str_replace('{{jsvalidator}}', $jsvalidation, $stub);
        return $this;
    }
	
	protected function replaceRelatedModels(&$stub, $relatedModels, $relatedModelsItems)
    {
        $stub = str_replace(['{{relatedModels}}','{{relatedModelsItems}}'], [$relatedModels, $relatedModelsItems], $stub);
        return $this;
    }
		
	protected function replaceAjaxData(&$stub, $ajaxData)
    {
        $stub = str_replace('{{ajaxData}}', $ajaxData, $stub);
        return $this;
    }

}
