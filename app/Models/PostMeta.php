<?php
namespace Models;
use Eloquent;

class PostMeta extends Eloquent
{
    protected $table = 'post_meta';
    protected static $used_meta = array();    

	public static function getMeta($id, $key, $use_cache = true)
	{
        $cache_k = $id.'_'.$key;
        if($use_cache){
            if(isset(self::$used_meta[$cache_k])){
                return self::$used_meta[$cache_k];
            }
        }
		$get = PostMeta::where('post_id', '=', $id)->where('meta_key', '=', $key)->first();
		if(!$get){
			return false;
		}
        self::$used_meta[$cache_k] = $get->meta_value;
		return $get->meta_value;
	}
	
	public static function setMeta($id, $key, $value)
	{
		$get = PostMeta::where('post_id', '=', $id)->where('meta_key', '=', $key)->first();
		if(!$get){
			$get = new PostMeta;
			$get->post_id = $id;
			$get->meta_key = $key;
		}
		$get->meta_value = $value;
		$get->save();
		return true;
	}
	
	public static function cleanMeta($id, $key)
	{
		$get = PostMeta::where('post_id', '=', $id)->where('meta_key', '=', $key)->first();
		if($get){
			$get->delete();
		}
		return true;
	}
}
