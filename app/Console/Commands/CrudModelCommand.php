<?php

namespace App\Console\Commands;
use Illuminate\Console\GeneratorCommand;
use Appzcoder\CrudGenerator\Commands\CrudModelCommand as cmc;
class CrudModelCommand extends cmc
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:jmodel
                            {name : The name of the model.}
                            {--table= : The name of the table.}
                            {--fillable= : The names of the fillable columns.}
                            {--relationships= : The relationships for the model}
                            {--pk=id : The name of the primary key.}
                            {--soft-deletes=no : Include soft deletes fields.}';

   
    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        $table = $this->option('table') ?: $this->argument('name');
        $fillable = $this->option('fillable');
        $primaryKey = $this->option('pk');
        $relationships = trim($this->option('relationships')) != '' ? explode(';', trim($this->option('relationships'))) : [];
        $softDeletes = $this->option('soft-deletes');
        if (!empty($primaryKey)) {
            $primaryKey = <<<EOD
/**
    * The database primary key value.
    *
    * @var string
    */
    protected \$primaryKey = '$primaryKey';
EOD;
        }

        $ret = $this->replaceNamespace($stub, $name)
            ->replaceTable($stub, $table)
            ->replaceFillable($stub, $fillable)
            ->replacePrimaryKey($stub, $primaryKey)
            ->replaceSoftDelete($stub, $softDeletes);

        foreach ($relationships as $rel) {
            $parts = explode('#', $rel);

            if (count($parts) != 3) {
                continue;
            }

            // blindly wrap each arg in single quotes
            $args = explode('|', trim($parts[2]));
			$argsString ="";
			if (trim($args[0]) != '') {
				$cls = starts_with($args[0],'\\')?$args[0]:'\\'.$args[0];
				$argsString .=  $cls.'::class, ';
				unset($args[0]);
            }
            foreach ($args as $k => $v) {
                if (trim($v) == '') {
                    continue;
                }
                $argsString .= "'" . trim($v) . "', ";
            }

            $argsString = substr($argsString, 0, -2); // remove last comma
            $ret->createRelationshipFunction($stub, trim($parts[0]), trim($parts[1]), $argsString);
        }

        $ret->replaceRelationshipPlaceholder($stub);

        return $ret->replaceClass($stub, $name);
    }

  
}
