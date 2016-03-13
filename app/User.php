<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'birthday',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setBirthdayAttribute($birthday) {
        if (!!$birthday) {
            $this->attributes['birthday'] = date('Y-m-d', strtotime($birthday));
        }
    }

    /**
     * Get the histories/ratings of beer for the user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function history()
    {
        return $this->hasMany('App\History');
    }
}
