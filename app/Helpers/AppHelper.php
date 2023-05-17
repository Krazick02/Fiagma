<?php
namespace App\Helpers;

use Illuminate\Support\Facades\File;

class AppHelper 
{
	public static function store_file($request,$file,$model,$folder_path,$name_file)
	{
		if(is_null($name_file)){
			$name_file = $model->id."_".time().".".$file->getClientOriginalExtension();
		}
        
        $file->storeAs(
            'public/'.$folder_path, $name_file
        );
        return $name_file;
	}

	public static function delete_file($model,$field,$folder_path)
	{
		$path = storage_path()."/app/public/".$folder_path."/".$model->{$field};

        if(File::exists($path)){
        	$model->{$field} = null;
            File::delete($path);
            return true;
        }
        return false;
	}
}