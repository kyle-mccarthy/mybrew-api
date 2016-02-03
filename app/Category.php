<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'parent_id'];

    /**
     * Get the beers in the category
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beers()
    {
        return $this->hasMany('App\Beer');
    }
}
