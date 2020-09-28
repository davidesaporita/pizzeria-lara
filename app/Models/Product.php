<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'name', 'description', 'price', 'image'];

    /******* RELATIONSHIPS *******/

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }    

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order')->withPivot('quantity');
    }    
}
