<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function orderItems()
    {
        return $this->hasMany('App\OrderItem', 'order_id');
    }
    public function cancelOrders()
    {
        return $this->hasOne('App\CancelOrder', 'order_id');
    }
    public function shippingInfos()
    {
        return $this->belongsTo('App\ShippingInfo');
    }
    public function shippingDetails()
    {
        return $this->hasMany('App\ShippingDetail', 'order_id');
    }
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
