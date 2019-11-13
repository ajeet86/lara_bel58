<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function blogs()
    {
        return $this->belongsTo('App\Blog');
    }
    
    public function podcast()
    {
        return $this->belongsTo('App\PodCast');
    }
    
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
