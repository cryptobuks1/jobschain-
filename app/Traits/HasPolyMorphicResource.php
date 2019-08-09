<?php 
namespace App\Traits;
trait HasPolyMorphicResource
{

    /** action_id , action_type  morphto
     * $this->morphResource('action',$this->action_type),.
     */
    protected  function morphResource($item,$resource_type)
    {
		$class = $this->class_name($resource_type);
		$transformer = "\\App\\Http\\Resources\\".ucfirst($class);
        return new $transformer($this->whenLoaded($item));
    }
	
	function class_name($classname)
	{
		if ($pos = strrpos($classname, '\\')) return substr($classname, $pos + 1);
		return $pos;
	}
}