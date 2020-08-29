<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'code', 'description', 'price', 'guarantee', 'available', 'brand',
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}

