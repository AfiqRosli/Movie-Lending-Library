<?php

namespace App;

use App\Movie;
use App\Member;
use Illuminate\Database\Eloquent\Model;

class Lend extends Model
{
    protected $table = 'lends';

    protected $fillable = [
        'movie_id',
        'member_id',
        'lateness_charge',
    ];

    public $timestamps = false;

    public function member() {
        return $this->belongsTo('App\Member');
    }

    public function movie() {
        return $this->hasOne('App\Movie');
    }

    public function getMovieTitle() {
        return Movie::find($this->movie_id)->title;
    }

    public function getMemberName() {
        return Member::find($this->member_id)->name;
    }
}
