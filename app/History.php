<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';
    protected $fillable = ['user_id', 'beer_id', 'rating', 'notes'];
}
