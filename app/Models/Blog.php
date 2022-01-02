<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;



    protected $fillable = [
        'title',
        'content',
        'image',
        'status'
    ];

    public function getImageAttribute($val)
    {
        // return Storage::url("app/public/".$val);
        return asset($val);
    }
}
