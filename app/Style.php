<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    protected $table = 'styles';
    protected $fillable = ['name', 'category_id'];

    /**
     * Get the general category that the style of beer belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the beers that are of the particular style
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function beers()
    {
        return $this->hasMany('App\Beer');
    }
}
