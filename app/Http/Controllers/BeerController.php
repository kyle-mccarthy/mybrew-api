<?php namespace App\Http\Controllers;

use App\Beer;

class BeerController extends Controller
{
    /**
     * Get a list of all of the beers in the database
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $beers = Beer::with('brewery')->get();
        return response($beers);
    }
}