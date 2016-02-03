<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beer extends Model
{
    protected $table = 'beers';
    protected $fillable = ['name', 'brewery_id', 'category_id', 'style_id', 'body', 'sweetness', 'color_id',
        'abv', 'ibu', 'hoppiness', 'maltiness', 'description'];
}
