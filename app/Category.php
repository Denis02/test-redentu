<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'title', 'rubric', 'brand',
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }
}
