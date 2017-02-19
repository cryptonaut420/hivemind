<?php
namespace Models;
use Eloquent, User;

class Post extends Eloquent
{

    public function user()
    {
        return User::find($this->user_id);
    }
    
    public function meta($full = false)
    {
        $get = PostMeta::where('post_id', $this->id)->get();
        if($full){
            return $get;
        }
        $output = array();
        if($get){
            foreach($get as $row)[
                $output[$row->meta_key] = $row->meta_value;
            }
        }
        return $output;
    }
    
    public function comments()
    {
        $coments = PostComment::where('post_id', $this->id)->orderBy('id', 'asc')->get();
        return $comments;
    }
    
    public function tags()
    {
        $get = PostTag::where('post_id', $this->id)->get();
        return $get;
    }
    
    public function ratings()
    {
        $get = PostRating::where('post_id', $this->id)->get();
        return $get;
    }


}
