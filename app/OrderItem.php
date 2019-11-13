<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function orders()
    {
        return $this->belongsTo('App\Order');
    }
    
    public function books()
    {
        return $this->belongsTo('App\Book');
    }
}
