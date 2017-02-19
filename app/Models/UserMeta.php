<?php
namespace Models;
use Eloquent;

class UserMeta extends Eloquent
{
	protected $table = 'user_meta';
    protected static $used_meta = array();
	
	public static function allUser($id)
	{
		$getAll = UserMeta::where('userId', '=', $id)->all();
		$output = array();
		foreach($getAll as $row){
			$output[$row->metaKey] = $row->value;
		}
		return $output;
	}
	
	public static function getMeta($id, $key, $use_cache = true)
	{
        $cache_k = $id.'_'.$key;
        if($use_cache){
            if(isset(self::$used_meta[$cache_k])){
                return self::$used_meta[$cache_k];
            }
        }
		$get = UserMeta::where('userId', '=', $id)->where('metaKey', '=', $key)->first();
		if(!$get){
			return false;
		}
        self::$used_meta[$cache_k] = $get->value;
		return $get->value;
	}
	
	public static function setMeta($id, $key, $value)
	{
		$get = UserMeta::where('userId', '=', $id)->where('metaKey', '=', $key)->first();
		if(!$get){
			$get = new UserMeta;
			$get->userId = $id;
			$get->metaKey = $key;
		}
		$get->value = $value;
		$get->save();
		return true;
	}
	
	public static function cleanMeta($id, $key)
	{
		$get = UserMeta::where('userId', '=', $id)->where('metaKey', '=', $key)->first();
		if($get){
			$get->delete();
		}
		return true;
	}

}
