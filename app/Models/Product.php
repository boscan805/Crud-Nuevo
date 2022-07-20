<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $filliable = ['name', 'price', 'stock', 'images', 'url'];

    public function imagesproducts() {
        return $this->hasMany('App\Models\Image');
    }
}
