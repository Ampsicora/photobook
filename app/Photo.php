<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'user_id', 'token', 'token_death_date'
    ];


    public function tags()
    {
        return $this->hasMany('App\Tag');
    }
}
