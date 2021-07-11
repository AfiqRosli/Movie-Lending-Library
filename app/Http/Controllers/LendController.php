<?php

namespace App\Http\Controllers;

use App\Lend;
use App\Member;
use App\Movie;
use App\Enums\MemberState;
use Illuminate\Http\Request;

class LendController extends Controller
{
    public function home() {
        return view('lend.home');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lends = Lend::all();

        return view('lend.index', compact('lends'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activeMembers = Member::where('is_active', '=', MemberState::Active)->get();

        $neverLendedMovies = Movie::doesntHave('lend')->get();

        // Returned movies may get lended again
        $returnedMovies = Movie::whereHas('lend', function ($query) {
            $query->where('returned_date', '<>', null);
        })->get();

        // Currently lended movies may exist in the $returnedMovies variable;
        // Was returned (update initial record) but lended again (create duplicate record with null value on returned_date)
        $lendedMovies = Movie::whereHas('lend', function ($query) {
            $query->where('returned_date', '=', null);
        })->get();

        $returnedMoviesInPossession = $returnedMovies->diff($lendedMovies);

        $availableMovies = $neverLendedMovies->merge($returnedMoviesInPossession);

        return view('lend.create', compact('activeMembers', 'availableMovies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lend = new Lend($request->lend);
        $lend->lending_date = date('Y-m-d H:i:s');
        $lend->created_time = date('Y-m-d H:i:s');

        $lend->save();

        return response()->json([
            'lend_added' => true
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function show(Lend $lend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function edit(Lend $lend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lend $lend)
    {
        $lend->update($request->lend);

        $lend->returned_date = date('Y-m-d H:i:s');

        $lend->save();

        return response()->json([
            'updated_lend' => $lend
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lend $lend)
    {
        //
    }
}
