<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
   protected $fillable = [
      'codeno','name','photo','price','discount','description','brand_id','subcategory_id',
    ];

    public function brand()
    {
    	return $this->belongTo('App\Brand');
    }

    public function subcategories()
    {
    	return $this->belongTo('App\Subcategory');
    }
}
