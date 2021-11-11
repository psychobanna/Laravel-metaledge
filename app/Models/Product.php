<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "price",
        "discount_price",
        "description",
        "category",
        "subcategory",
        "image",
        "status",
    ];

    public function getImageAttribute($val)
    {
        // return Storage::url("app/public/".$val);
        return asset($val);
    }

    public function getCategoryAttribute($val){
        return Category::where('id',$val)->select('id','name')->first();
    }

    public function getSubcategoryAttribute($val){
        return Category::where('id',$val)->select('id','name')->first();
    }
}
