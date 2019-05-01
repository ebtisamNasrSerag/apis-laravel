<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'desc', 'images'
    ];

    /**
    *images relationship function
    *
    */
    public function images()
    {
        return $this->hasMany(ProImage::class);
    }

    /**
    *favorites relationship function
    *
    */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
