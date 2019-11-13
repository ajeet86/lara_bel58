<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingInfo extends Model
{
    public $timestamps = true;
    protected $fillable = ['user_id', 'email', 'first_name', 'last_name', 
        'address', 'apartment', 'country', 'state', 'city', 
        'pin_code', 'phone_number'];
    
    public function orders()
    {
        return $this->hasMany('App\Order', 'shipping_id');
    }
    
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
