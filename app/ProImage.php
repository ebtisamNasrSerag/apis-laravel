<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProImage extends Model
{
    protected $fillable = [
        'image'
    ];
    
    /**
    *product relationship function
    */
    public function product(){
	 return $this->belongsTo(Product::class);
	}
}
