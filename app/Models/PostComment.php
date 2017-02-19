<?php
namespace Models;
use Eloquent, User;

class PostComment extends Eloquent
{
    
    public function post()
    {
        return Post::find($this->post_id);
    }
    
    public function user()
    {
        return User::find($this->user_id);
    }
    
    public function parentComment()
    {
        if($this->parent_id == 0){
            return false;
        }
        return PostComment::find($this->parent_id);
    }
    


}
