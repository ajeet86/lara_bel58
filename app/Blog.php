<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function blogtags()
    {
        return $this->hasMany('App\BlogTag', 'blog_id');
    }
    public function comments()
    {
        return $this->hasMany('App\Comment', 'blog_or_pod_id');
    }
}
