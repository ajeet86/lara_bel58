<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PodTag extends Model
{
    public function pods()
    {
        return $this->belongsTo('App\PodCast');
    }
}
