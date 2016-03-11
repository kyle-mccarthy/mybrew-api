<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $table = 'keywords';
    protected $fillable = ['name'];

    /**
     * Get the beers that use a particular keyword
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function beers()
    {
        return $this->belongsToMany('App\Beer');
    }
}
