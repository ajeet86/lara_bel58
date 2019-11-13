<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookImage extends Model
{
    public function books()
    {
        return $this->belongsTo('App\Book');
    }
        
}
