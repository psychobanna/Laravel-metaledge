<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'image',
        'status',
        'delete_status'
    ];

    public function getImageAttribute($val)
    {
        // return Storage::url("app/public/".$val);
        return asset($val);
    }
}
