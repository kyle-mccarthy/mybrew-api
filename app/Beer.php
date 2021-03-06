<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beer extends Model
{
    protected $table = 'beers';
    protected $fillable = ['name', 'brewery_id', 'category_id', 'style_id', 'color_id', 'abv', 'ibu', 'description'];

    /**
     * Get the brewery that the beer belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brewery()
    {
        return $this->belongsTo('App\Brewery');
    }

    /**
     * Get the color for the beer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function color()
    {
        return $this->belongsTo('App\Color');
    }

    /**
     * Get the history of all the ratings for the beer
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('App\History');
    }

    /**
     * Get the keywords for the particular beer through the many to many relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keywords()
    {
        return $this->belongsToMany('App\Keyword', 'beers_keywords');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function style()
    {
        return $this->belongsTo('App\Style');
    }
}
