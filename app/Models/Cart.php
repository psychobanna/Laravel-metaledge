<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'product',
        'qty'
    ];

    public function getProductAttribute($val){
        return Product::where('id',$val)->first();
    }

    public function getCustomerAttribute($val){
        return customer::where('id',$val)->first();
    }
}
