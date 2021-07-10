<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lend extends Model
{
    protected $table = 'lends';

    protected $fillable = [
        'movie_id',
        'member_id',
        'lending_date',
    ];

    public $timestamps = false;

    public function member() {
        return $this->belongsTo('App\Member');
    }

    public function movie() {
        return $this->hasOne('App\Movie');
    }
}
