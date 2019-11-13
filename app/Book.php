<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function category()
    {
    	return $this->belongsTo('App\Category');
    }
    public function bookImages()
    {
        return $this->hasMany('App\BookImage', 'book_id');
    }
	public function bookAuthors()
    {
        return $this->hasMany('App\Author', 'book_id');
    }
    public function orderItems()
    {
        return $this->hasMany('App\OrderItem', 'book_id');
    }
}
