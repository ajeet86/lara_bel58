<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PodCast extends Model
{
    public function comments()
    {
        return $this->hasMany('App\Comment', 'blog_or_pod_id');
    }
    public function podtags()
    {
        return $this->hasMany('App\PodTag', 'pod_id');
    }
}
