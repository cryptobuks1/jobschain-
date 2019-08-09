<?php
namespace App\Console\Commands;
use Appzcoder\CrudGenerator\Commands\CrudMigrationCommand as GeneratorCommand;
use Illuminate\Support\Str;
class CrudMigrationCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:jmigration
                            {name : The name of the migration.}
                            {--schema= : The name of the schema.}
                            {--indexes= : The fields to add an index to.}
                            {--foreign-keys= : Foreign keys.}
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
        $tableName = $this->argument('name');
        $className = 'Create' . str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName))) . 'Table';

        $fieldsToIndex = trim($this->option('indexes')) != '' ? explode(',', $this->option('indexes')) : [];
        $foreignKeys = trim($this->option('foreign-keys')) != '' ? explode(',', $this->option('foreign-keys')) : [];

        $schema = rtrim($this->option('schema'), ';');
        $fields = explode(';', $schema);

        $data = array();

        if ($schema) {
            $x = 0;
            foreach ($fields as $field) {
				
                $fieldArray = explode('#', $field);
                $data[$x]['name'] = trim($fieldArray[0]);
				$types = explode('|',$fieldArray[1]);
				$type = array_shift($types);
                $data[$x]['type'] = $type;
                if ((Str::startsWith($type, 'select')|| Str::startsWith($type, 'enum')) && isset($fieldArray[2])) {
                    $options = trim($fieldArray[2]);
                    $data[$x]['options'] = str_replace('options=', '', $options);
                }
				//string:30|default:'ofumbi'|nullable
				$data[$x]['modifiers'] = [];
				if(count($types)){
					$modifierLookup = [
						'comment',
						'default',
						'first',
						'nullable',
						'unsigned',
						'unique',
						'charset',
					];
					foreach($types as $modification){
						$variables = explode(':',$modification);
						$modifier =   array_shift($variables);
						if(!in_array(trim($modifier), $modifierLookup)) continue;
						$variables = $variables[0]??"";
						$data[$x]['modifiers'][] = "->" . trim($modifier) . "(".$variables.")";
					}
				}
                $x++;
            }
        }

        $tabIndent = '    ';

        $schemaFields = '';
        foreach ($data as $item) {
			$data_type = explode(':',$item['type']);
			$item_type = array_shift($data_type);
			$variables = isset($data_type[0])?",".$data_type[0]:"";
            if (isset($this->typeLookup[$item_type ])) {
                $type = $this->typeLookup[$item_type];
                if (!empty($item['options'])) {
                    $enumOptions = array_keys(json_decode($item['options'], true));
                    $enumOptionsStr = implode(",", array_map(function ($string) {
                        return '"' . $string . '"';
                    }, $enumOptions));
                    $schemaFields .= "\$table->enum('" . $item['name'] . "', [" . $enumOptionsStr . "])";
                } elseif($item['name']=="uuid") {
                    $schemaFields .= "\$table->uuid('" . $item['name'] . "')";
                }else {
                    $schemaFields .= "\$table->" . $type . "('" . $item['name'] . "'".$variables.")";
                }
            } else {
               	 if (!empty($item['options'])) {
                    $enumOptions = array_keys(json_decode($item['options'], true));
                    $enumOptionsStr = implode(",", array_map(function ($string) {
                        return '"' . $string . '"';
                    }, $enumOptions));
                    $schemaFields .= "\$table->enum('" . $item['name'] . "', [" . $enumOptionsStr . "])";
                } elseif($item['name']=="uuid") {
                    $schemaFields .= "\$table->uuid('" . $item['name'] . "')";
                }else {
                     $schemaFields .= "\$table->string('" . $item['name'] . "'".$variables.")";
                }
            }

            // Append column modifier
            $schemaFields .= implode("",$item['modifiers']);
            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        // add indexes and unique indexes as necessary
        foreach ($fieldsToIndex as $fldData) {
            $line = trim($fldData);

            // is a unique index specified after the #?
            // if no hash present, we append one to make life easier
            if (strpos($line, '#') === false) {
                $line .= '#';
            }

            // parts[0] = field name (or names if pipe separated)
            // parts[1] = unique specified
            $parts = explode('#', $line);
            if (strpos($parts[0], '|') !== 0) {
                $fieldNames = "['" . implode("', '", explode('|', $parts[0])) . "']"; // wrap single quotes around each element
            } else {
                $fieldNames = trim($parts[0]);
            }

            if (count($parts) > 1 && $parts[1] == 'unique') {
                $schemaFields .= "\$table->unique(" . trim($fieldNames) . ")";
            } else {
                $schemaFields .= "\$table->index(" . trim($fieldNames) . ")";
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        // foreign keys
        foreach ($foreignKeys as $fk) {
            $line = trim($fk);

            $parts = explode('#', $line);

            // if we don't have three parts, then the foreign key isn't defined properly
            // --foreign-keys="foreign_entity_id#id#foreign_entity#onDelete#onUpdate"
            if (count($parts) == 3) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')";
            } elseif (count($parts) == 4) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')"
                . "->onDelete('" . trim($parts[3]) . "')" . "->onUpdate('" . trim($parts[3]) . "')";
            } elseif (count($parts) == 5) {
                $schemaFields .= "\$table->foreign('" . trim($parts[0]) . "')"
                . "->references('" . trim($parts[1]) . "')->on('" . trim($parts[2]) . "')"
                . "->onDelete('" . trim($parts[3]) . "')" . "->onUpdate('" . trim($parts[4]) . "')";
            } else {
                continue;
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        $primaryKey = $this->option('pk');
        $softDeletes = $this->option('soft-deletes');

        $softDeletesSnippets = '';
        if ($softDeletes == 'yes') {
            $softDeletesSnippets = "\$table->softDeletes();\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        $schemaUp =
            "Schema::create('" . $tableName . "', function (Blueprint \$table) {
            \$table->bigIncrements('" . $primaryKey . "');
            \$table->timestamps();\n" . $tabIndent . $tabIndent . $tabIndent .
            $softDeletesSnippets .
            $schemaFields .
        "});";

        $schemaDown = "Schema::drop('" . $tableName . "');";

        return $this->replaceSchemaUp($stub, $schemaUp)
            ->replaceSchemaDown($stub, $schemaDown)
            ->replaceClass($stub, $className);
    }

  
}
