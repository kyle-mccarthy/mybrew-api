<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brewery extends Model
{
    protected $table = 'breweries';
    protected $fillable = ['name', 'location'];

    /**
     * Get the beers that belong to the brewery
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beers()
    {
        return $this->hasMany('App\Beer');
    }
}
