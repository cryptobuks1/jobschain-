<?php

namespace App\Console\Commands;
use Appzcoder\CrudGenerator\Commands\CrudViewCommand as cvc;
use Illuminate\Support\Str;
use File;
class CrudViewCommand extends cvc
{
	 protected $emptyVueForm = '';
	 protected $filledVueForm = '';
	 protected $vars = [
        'formFields',
        'formFieldsHtml',
        'varName',
        'crudName',
        'crudNameCap',
        'crudNameSingular',
        'primaryKey',
        'modelName',
        'modelNameCap',
        'viewName',
        'routePrefix',
        'routePrefixCap',
        'routeGroup',
        'formHeadingHtml',
        'formBodyHtml',
        'viewTemplateDir',
        'formBodyHtmlForShowView',
        'filledVueForm',
        'emptyVueForm',
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:jview
                            {name : The name of the Crud.}
                            {--fields= : The field names for the form.}
                            {--view-path= : The name of the view path.}
                            {--route-group= : Prefix of the route group.}
                            {--pk=id : The name of the primary key.}
                            {--validations= : Validation rules for the fields.}
                            {--form-helper=html : Helper for the form.}
                            {--custom-data= : Some additional values to use in the crud.}
                            {--localize=no : Localize the view? yes|no.}
							{--stub-path= : Optional name of the stubs folder in the view stubs dir.}';
	/**
     *  Form field types collection.
     *
     * @var array
     */
    protected $typeLookup = [
        'string' => 'text',
        'char' => 'text',
        'varchar' => 'text',
        'text' => 'textarea',
        'mediumtext' => 'textarea',
        'longtext' => 'textarea',
        'json' => 'textarea',
        'jsonb' => 'textarea',
        'binary' => 'textarea',
        'password' => 'password',
        'email' => 'email',
        'number' => 'number',
        'integer' => 'number',
        'bigint' => 'number',
        'mediumint' => 'number',
        'tinyint' => 'number',
        'smallint' => 'number',
        'decimal' => 'number',
        'double' => 'number',
        'float' => 'number',
        'date' => 'date',
        'datetime' => 'datetime-local',
        'timestamp' => 'datetime-local',
        'time' => 'time',
        'radio' => 'radio',
        'boolean' => 'radio',
        'enum' => 'select',
        'select' => 'select',
        'file' => 'file',
        'uuid' => 'text',
    ];

  
    public function handle()
    {
        $formHelper = $this->option('form-helper');
        $stubs =  ($this->option('stub-path')) ?$this->option('stub-path').'/':"";
        $this->viewDirectoryPath = config('crudgenerator.custom_template')
            ? config('crudgenerator.path') . 'views/'. $stubs . $formHelper . '/'
            : __DIR__ . '/../stubs/views/' . $formHelper . '/';
        $this->crudName = strtolower($this->argument('name'));
        $this->varName = lcfirst($this->argument('name'));
        $this->crudNameCap = ucwords($this->crudName);
        $this->crudNameSingular = str_singular($this->crudName);
        $this->modelName = str_singular($this->argument('name'));
        $this->modelNameCap = ucfirst($this->modelName);
        $this->customData = $this->option('custom-data');
        $this->primaryKey = $this->option('pk');
        $this->routeGroup = ($this->option('route-group'))
            ? $this->option('route-group') . '/'
            : $this->option('route-group');
        $this->routePrefix = ($this->option('route-group')) ? $this->option('route-group') : '';
        $this->routePrefixCap = ucfirst($this->routePrefix);
        $this->viewName = snake_case($this->argument('name'), '-');

        $viewDirectory = config('view.paths')[0] . '/';
        if ($this->option('view-path')) {
            $this->userViewPath = $this->option('view-path');
            $path = $viewDirectory . $this->userViewPath . '/' . $this->viewName . '/';
        } else {
            $path = $viewDirectory . $this->viewName . '/';
        }

        $this->viewTemplateDir = isset($this->userViewPath)
            ? $this->userViewPath . '.' . $this->viewName
            : $this->viewName;

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $fields = $this->option('fields');
        $fieldsArray = explode(';', $fields);

        $this->formFields = [];

        $validations = $this->option('validations');

        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $item) {
                $itemArray = explode('#', $item);
				if(Str::contains( $itemArray[1],':')||Str::contains( $itemArray[1],'|')){
					$types = explode('|', str_replace(':', "|", $itemArray[1]));
					$type = $types[0];
				}else{
					$type = $itemArray[1];
				}
                $this->formFields[$x]['name'] = trim($itemArray[0]);
                $this->formFields[$x]['type'] = trim($type);
                $this->formFields[$x]['required'] = preg_match('/' . $itemArray[0] . '/', $validations) ? true : false;

                if ( $this->formFields[$x]['type'] == 'select' && isset($itemArray[2])) {
                    $options = trim($itemArray[2]);
                    $options = str_replace('options=', '', $options);
                    $this->formFields[$x]['options'] = $options;
                }
				 if ($this->formFields[$x]['type'] != 'select' &&  isset($itemArray[3])&&Str::startsWith($itemArray[3],'options')) {
                    $options = trim($itemArray[3]);
                    $options = str_replace('options=', '', $options);
                    $this->formFields[$x]['options'] = $options;
                }
	
                $x++;
            }
        }

        foreach ($this->formFields as $item) {
            $this->formFieldsHtml .= $this->createField($item);
        }
		$tb = '    ';
		
        $i = 0;
        foreach ($this->formFields as $key => $value) {
            /*if ($i == $this->defaultColumnsToShow) {
                break;
            }*/

            $field = $value['name'];
            $label = ucwords(str_replace('_', ' ', $field));
            if ($this->option('localize') == 'yes') {
                $label = '{{ __(\'' . $this->crudName . '.' . $field . '\') }}';
            }
            $this->formHeadingHtml .= $tb.$tb.$tb.$tb.$tb.$tb.'<th>' . $label . '</th>'.PHP_EOL;;
           // $this->formBodyHtml .=  $tb.$tb.$tb.$tb.$tb.$tb.'<td>{{ %%crudNameSingular%%.'. $field .'}}</td>'.PHP_EOL;;
			$this->formBodyHtml.= $tb.$tb.$tb.$tb.$tb.'{data: \''.$field.'\', name:  \''.$field.'\', orderable: true},'.PHP_EOL;
            $this->formBodyHtmlForShowView .= '<tr><th> ' . $label . ' </th><td> {{ $%%crudNameSingular%%->' . $field . ' }} </td></tr>';
			$this->emptyVueForm.= $tb.$tb.$tb.$tb.$field.': "",'.PHP_EOL;
			$this->filledVueForm.= $tb.$tb.$tb.$tb.$field.':'.$this->crudNameSingular.'.'.$field.','.PHP_EOL;
            $i++;
			
        }
		//dd($this->emptyVueForm ,$this->filledVueForm );
        $this->templateStubs($path);

        $this->info('View created successfully.');
    }
	
	
	protected function createFormField($item)
    {
        $start = $this->delimiter[0];
        $end = $this->delimiter[1];
        $required = $item['required'] ? 'required' : '';
        $markup = File::get($this->viewDirectoryPath . 'form-fields/form-field.blade.stub');
		$name = $item['name'];
		if(isset($item['options'])){ // for relationship selects
			$markup = File::get($this->viewDirectoryPath . 'form-fields/select-relationship-field.blade.stub');
			$name = $item['options'];
		}
        $markup = str_replace($start . 'required' . $end, $required, $markup);
        $markup = str_replace($start . 'fieldType' . $end, $this->typeLookup[$item['type']], $markup);
        $markup = str_replace($start . 'itemName' . $end, $name, $markup);
        $markup = str_replace($start . 'crudNameSingular' . $end, $this->crudNameSingular, $markup);

        return $this->wrapField(
            $item,
            $markup
        );
    }
	protected function wrapField($item, $field)
    {
        $formGroup = File::get($this->viewDirectoryPath . 'form-fields/wrap-field.blade.stub');

        $labelText = ucwords(strtolower(str_replace('_', ' ', $item['name'])));

        if ($this->option('localize') == 'yes') {
            $labelText = '__(\'' . $this->crudName . '.' . $item['name'] . '\')';
        }

        return sprintf($formGroup, $item['name'], $labelText, $field);
    }
	
	 /**
     * Generate files from stub
     *
     * @param $path
     */
    protected function templateStubs($path)
    {
        $dynamicViewTemplate = config('crudgenerator.dynamic_view_template')
            ? config('crudgenerator.dynamic_view_template')
            : $this->defaultTemplating();
		
 
		
        foreach ($dynamicViewTemplate as $name => $vars) {
            $file = $this->viewDirectoryPath . $name . '.blade.stub';
            $newFile = $path . $name . '.blade.php';
			$vuefile = $this->viewDirectoryPath . $name . '.vue.stub';
            $newVueFile = resource_path('js/components/'.ucfirst($this->crudName).'.vue');
		
            if (!File::copy($file, $newFile)) {
                echo "failed to copy $file...\n";
            } else {
                $this->templateVars($newFile, $vars);
                $this->userDefinedVars($newFile);
            }
			
			if(File::exists($vuefile)&&File::isFile($vuefile)){
				if (!File::copy($vuefile, $newVueFile)) {
					echo "failed to copy vuejs $file...\n";
				} else {
					$this->templateVars($newVueFile, $vars);
					$this->userDefinedVars($newVueFile);
				}
			}
        }
    }




}
