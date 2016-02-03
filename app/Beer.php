<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beer extends Model
{
    protected $table = 'beers';
    protected $fillable = ['name', 'brewery_id', 'category_id', 'style_id', 'body', 'sweetness', 'color_id',
        'abv', 'ibu', 'hoppiness', 'maltiness', 'description'];

    /**
     * Get the brewery that the beer belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brewery()
    {
        return $this->belongsTo('App\Brewery');
    }

    /**
     * Get the category that the beer belongs to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Aop\Category');
    }

    /**
     * Get the color for the beer
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function color()
    {
        return $this->belongsTo('App\Color');
    }
}
