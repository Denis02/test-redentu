<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'price', 'guarantee', 'available',
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }
}

