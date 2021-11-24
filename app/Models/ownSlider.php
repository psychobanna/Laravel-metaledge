<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ownSlider extends Model
{
    use HasFactory;


    protected $fillable = [
        "title",
        "content",
        "link",
        "image",
        "status",
    ];

    public function getImageAttribute($val)
    {
        // return Storage::url("app/public/".$val);
        return asset($val);
    }

}
