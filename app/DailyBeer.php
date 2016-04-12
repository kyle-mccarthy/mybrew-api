<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyBeer extends Model
{
    protected $table = 'daily_beers';
    protected $fillable = ['beer_id'];

    /**
     * Get the beer for the specific day
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beer()
    {
        return $this->belongsTo('App\Beer');
    }
}
