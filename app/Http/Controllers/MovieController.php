<?php

namespace App\Http\Controllers;

use App\Movie;
use App\Enums\MovieGenre;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::all();
        $movieGenres = MovieGenre::getValues();

        return view('movie.index', compact('movies', 'movieGenres'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $movie = new Movie;

        $movie->title = $request->movie['title'];
        $movie->genre = $request->movie['genre'];
        $movie->released_date = date('Y-m-d', strtotime($request->movie['date']));
        $movie->created_time = date('Y-m-d H:i:s');

        $movie->save();

        return response()->json([
            'added_movie' => $movie
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $movie->title = $request->movie['title'];
        $movie->genre = $request->movie['genre'];
        $movie->released_date = date('Y-m-d', strtotime($request->movie['date']));

        $movie->save();

        return response()->json([
            'updated_movie' => $movie
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json([
            'movie_deleted' => true
        ], 200);
    }
}
