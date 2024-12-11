<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{

    protected $table = 'products';
    public $timestamps = true;

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }

    public function sizes()
    {
        return $this->belongsToMany('App\Models\Size');
    }

}