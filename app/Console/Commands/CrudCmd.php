<?php

namespace App\Console\Commands;
use File;
use Illuminate\Support\Str;
class CrudCmd extends \Appzcoder\CrudGenerator\Commands\CrudCommand
{
	/*
	// --only= policy,transformer,controller,model,migration,view,route
	//--view-path=bracket custom theme eg bracket, resources/crud-generator/view/bracket

	//--- backend example
	php artisan crud:gen Saas --fields_from_file="crud/saas.json" --view-path=_admin --controller-namespace=Admin --route-group=admin --form-helper=html --model-namespace=Models --soft-deletes=yes --localize=yes  --locales=en --stub-path=adminlte -vvv
	
	//--- frontend example
	php artisan crud:gen Saas --fields_from_file="crud/saas.json" --view-path=bracket --stub-path=bracket  --form-helper=laravelcollective --model-namespace=Models --soft-deletes=yes --only=view,controller -vvv
		*/
	 protected $signature = 'crud:gen
                            {name : The name of the Crud.}
                            {--fields= : Field names for the form & migration.}
                            {--fields_from_file= : Fields from a json file.}
                            {--validations= : Validation rules for the fields.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--model-namespace= : Namespace of the model inside "app" dir.}
                            {--pk=id : The name of the primary key.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : The foreign keys for the table.}
                            {--relationships= : The relationships for the model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--route-group= : Prefix of the route group.}
                            {--view-path= : The name of the view path.}
                            {--form-helper=html : Helper for generating the form.}
                            {--localize=no : Allow to localize? yes|no.}
                            {--locales=en : Locales language type.}
                            {--soft-deletes=no : Include soft deletes fields.}
							{--only=all  : Create only certain crude items.}
							{--stub-path= : Optional name of the stubs folder in the view stubs dir.}';
	
	
	/** @var string  */
    protected $routePrefix = '';
	
	
	public function handle()
    {
		$tabIndent = '    ';
        $name = $this->argument('name');
        $modelName = str_singular($name);
        $migrationName = str_plural(snake_case($name));
        $tableName = $migrationName;
        $routeGroup = $this->option('route-group');
        $this->routeName =   snake_case($name, '-');
		$this->routePrefix = ($routeGroup) ? $routeGroup . '.' . snake_case($name, '-') : snake_case($name, '-');
        $perPage = intval($this->option('pagination'));
        $controllerNamespace = ($this->option('controller-namespace')) ? $this->option('controller-namespace') . '\\' : '';
		$api = starts_with(strtolower($controllerNamespace),'api\\')||starts_with(strtolower($controllerNamespace),'ajax\\');
        $modelNamespace = ($this->option('model-namespace')) ? trim($this->option('model-namespace')) . '\\' : '';
        $fields = rtrim($this->option('fields'), ';');
        if ($this->option('fields_from_file')) {
            $fields = $this->processJSONFields($this->option('fields_from_file'));
        }
        $primaryKey = $this->option('pk');
        $viewPath = $this->option('view-path');

        $foreignKeys = $this->option('foreign-keys');

        if ($this->option('fields_from_file')) {
            $foreignKeys = $this->processJSONForeignKeys($this->option('fields_from_file'));
        }

        $validations = trim($this->option('validations'));
        if ($this->option('fields_from_file')) {
            $validations = $this->processJSONValidations($this->option('fields_from_file'));
        }
	
        $fieldsArray = explode(';', $fields);
        $fillableArray = [];
        $migrationFields = '';
		$transformArray = [];
		$resourceArray = [$tabIndent.$tabIndent.$tabIndent."'id'=>\$this->id,"];
        foreach ($fieldsArray as $item) {
            $spareParts = explode('#', trim($item));
            $transformArray[] = $tabIndent.$tabIndent.$tabIndent."'".$spareParts[0]."'=> \$".strtolower($modelName).'->'.$spareParts[0].',';
			$resourceArray[] = $tabIndent.$tabIndent.$tabIndent."'".$spareParts[0]."'=> \$this->".$spareParts[0].',';
            $fillableArray[] = $spareParts[0];
           // $modifier = !empty($spareParts[2]) ? $spareParts[2] : 'nullable';

            // Process migration fields
            $migrationFields .= $spareParts[0] . '#' . $spareParts[1];
			if(isset( $spareParts[2]))
            $migrationFields .= '#' . $spareParts[2];
            $migrationFields .= ';';
        }

        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";
		$commaSeparetedArray = implode("\n", $transformArray);
        $transform  = "[\n" . $commaSeparetedArray . "\n".$tabIndent.$tabIndent."]";
        $localize = $this->option('localize');
        $locales = $this->option('locales');
        $indexes = $this->option('indexes');
		$stubs = $this->option('stub-path');
        $relationships = $this->option('relationships');
        if ($this->option('fields_from_file')) {
            $relationships = $this->processJSONRelationships($this->option('fields_from_file'));
        }
		 $relations = explode(';',$relationships);
		if(count( $relations )){
			foreach ($relations as $item) {
				$parts = explode('#', $item);
				$relationshipName = $parts[0];
				$relationshipType = $parts[1]??null;
				if(is_null($relationshipType)) continue;
				$collection  = str_contains($relationshipType, 'Many');
				if (count($parts) != 3) {
					continue;
				}
				$args = explode('|', trim($parts[2]));
				$rl = explode('\\',$args[0]);
				$relationshipModel = end($rl) ;
				$resourceArray[] = $collection
					?$tabIndent.$tabIndent.$tabIndent."'".$relationshipName."'=> ".$relationshipModel."::collection(\$this->whenLoaded('".$relationshipName."')),"
					:$tabIndent.$tabIndent.$tabIndent."'".$relationshipName."'=> new ".$relationshipModel."(\$this->whenLoaded('".$relationshipName."')),";
			}
		}
		$commaResource = implode("\n", $resourceArray);
		$resource  = "[\n" . $commaResource . "\n".$tabIndent.$tabIndent."]";
        $formHelper = $this->option('form-helper');
        $softDeletes = $this->option('soft-deletes');
		
		$only = explode(',',$this->option('only'));
		$all = in_array('all',$only);
		if($all||in_array('policy',$only))
		$this->call('crud:policy', ['name' =>$modelName.'Policy', '--model' => $modelNamespace . $modelName]);
		if($all||in_array('transformer',$only))
		$this->call('crud:transformer', [
			'name' =>$modelName.'Transformer', 
			'--model' => $modelName,
			'--transform-array'=>$transform,
			'--model-namespace' =>$modelNamespace,
			'--relationships' =>$relationships,
		]);
		
		if($all||in_array('resource',$only))
		$this->call('crud:resource', [
			'name' =>$modelName, 
			'--resource-array'=>$resource,
		]);
		if($all||in_array('controller',$only)){
			$data = ['name' => $controllerNamespace . $name . 'Controller', '--crud-name' => $name, '--model-name' => $modelName, '--model-namespace' => $modelNamespace, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--pagination' => $perPage, '--fields' => $fields, '--validations' => $validations];
			if($api)$data['--api'] = "true";	
			$this->call('crud:jcontroller', $data ); 
		}
        
		if($all||in_array('model',$only)){
			 $this->call('crud:jmodel', ['name' => $modelNamespace . $modelName, '--fillable' => $fillable, '--table' => $tableName, '--pk' => $primaryKey, '--relationships' => $relationships, '--soft-deletes' => $softDeletes]);
			 //$this->call('crud:observer', ['name' =>$modelName.'Observer', '--model' => $modelNamespace . $modelName]);
		}
		if($all||in_array('migration',$only))
        $this->call('crud:jmigration', ['name' => $migrationName, '--schema' => $migrationFields, '--pk' => $primaryKey, '--indexes' => $indexes, '--foreign-keys' => $foreignKeys, '--soft-deletes' => $softDeletes]);
		if($all||in_array('view',$only))
        $this->call('crud:jview', ['name' => $name, '--fields' => $fields, '--validations' => $validations, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--localize' => $localize, '--pk' => $primaryKey, '--form-helper' => $formHelper ,'--stub-path'=>$stubs]);
        if ($localize == 'yes' &&($all||in_array('lang',$only))) {
            $this->call('crud:lang', ['name' => $name, '--fields' => $fields.";".$modelName."#".$modelName, '--locales' => $locales]);
        }
		
		
        // For optimizing the class loader
        if (\App::VERSION() < '5.6') {
            $this->callSilent('optimize');
        }
		if($all||in_array('route',$only)){
			
			// Updating the Http/routes.php file
			$routeFile = app_path('Http/routes.php');

			if (\App::VERSION() >= '5.3') {
				$routeFile = base_path('routes/web.php');
			}
			
			if ($api) {
				$routeFile = base_path('routes/api.php');
			}
			

			if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
				$this->controller =  $name . 'Controller';
				$routez = $api?$this->addApiRoutes():$this->addRoutes();
				$isAdded = File::append($routeFile, "\n" . implode("\n",$routez ));

				if ($isAdded) {
					$this->info('Crud/Resource route added to ' . $routeFile);
				} else {
					$this->info('Unable to add the route to ' . $routeFile);
				}
			}
		}
    }
	
	protected function processJSONFields($file)
    {
        $json = File::get($file);
        $fields = json_decode($json);

        $fieldsString = '';
		$relations = $fields->foreign_keys;
        foreach ($fields->fields as $field) {
            if (Str::startsWith($field->type , 'select') ||Str::startsWith( $field->type , 'enum')) {
                $fieldsString .= $field->name . '#' . $field->type . '#options=' . json_encode($field->options) . ';';
            } else {
				$name ="";
				if(substr($field->name , -3)=="_id" ){
					foreach($relations as $relation){
						if($relation->column != $field->name)continue;	
						$name = '#options='.str_replace("_id","",$field->name);
						break;
					}
				}
                $fieldsString .= $field->name . '#' . $field->type . $name .';';
            }
        }

        $fieldsString = rtrim($fieldsString, ';');
        return $fieldsString;
    }
	
	protected function addRoutes(){
		$tb = '    ';
		return [
$tb."Route::resource('" . $this->routeName . "', '" . $this->controller . "',[
".$tb.$tb."'names' => [
".$tb.$tb.$tb."'index'   => '".$this->routePrefix.".index',
".$tb.$tb.$tb."'create'   => '".$this->routePrefix.".create',
".$tb.$tb.$tb."'store'   => '".$this->routePrefix.".store',
".$tb.$tb.$tb."'show'   => '".$this->routePrefix.".show',
".$tb.$tb.$tb."'edit'   => '".$this->routePrefix.".edit',
".$tb.$tb.$tb."'update'   => '".$this->routePrefix.".update',
".$tb.$tb.$tb."'destroy' => '".$this->routePrefix.".destroy',
".$tb.$tb."],
".$tb."]);
".$tb."Route::post('" . $this->routeName . "/table',['as'=>'".$this->routePrefix.".table','uses'=>'" . $this->controller . "@table']); 
".$tb."Route::post('" . $this->routeName . "/toggle-status/{id}',['as'=>'".$this->routePrefix.".toggle_status','uses'=>'" . $this->controller . "@toggle_status']);
".$tb."Route::post('" . $this->routeName . "/mass-toggle',['as'=>'".$this->routePrefix.".masstoggle','uses'=>'" . $this->controller . "@toggle_statuses']);
".$tb."Route::post('" . $this->routeName . "/mass-delete',['as'=>'".$this->routePrefix.".massdelete','uses'=>'" . $this->controller . "@delete']);
"];
	}
	
	protected function addApiRoutes(){
		$tb = '    ';
		return [
$tb.$tb."Route::apiResource('" . $this->routeName . "', '" . $this->controller . "',[
".$tb.$tb.$tb."'names' => [
".$tb.$tb.$tb.$tb."'index'   => '".$this->routePrefix.".index',
".$tb.$tb.$tb.$tb."'store'   => '".$this->routePrefix.".store',
".$tb.$tb.$tb.$tb."'show'   => '".$this->routePrefix.".show',
".$tb.$tb.$tb.$tb."'update'   => '".$this->routePrefix.".update',
".$tb.$tb.$tb.$tb."'destroy' => '".$this->routePrefix.".destroy',
".$tb.$tb.$tb."],
".$tb.$tb."]);
".$tb.$tb."Route::post('" . $this->routeName . "/toggle-status/{id}',['as'=>'".$this->routePrefix.".toggle_status','uses'=>'" . $this->controller . "@toggle_status']);
".$tb.$tb."Route::post('" . $this->routeName . "/mass-toggle',['as'=>'".$this->routePrefix.".masstoggle','uses'=>'" . $this->controller . "@toggle_statuses']);
".$tb.$tb."Route::post('" . $this->routeName . "/mass-delete',['as'=>'".$this->routePrefix.".massdelete','uses'=>'" . $this->controller . "@delete']);
"];
	}
	 
}



