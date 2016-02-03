<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brewery extends Model
{
    protected $table = 'breweries';
    protected $fillable = ['name', 'location'];
}
