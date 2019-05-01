<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Product;

class Favorite extends Model
{

    /**
    *user relationship function
    *
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
    *product relationship function
    *
    */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
