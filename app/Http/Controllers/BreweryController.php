<?php namespace App\Http\Controllers;

use App\Brewery;

class BreweryController extends Controller
{
    /**
     * Get a list of the breweries that have beers in the data
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $breweries = Brewery::all();
        return response([
            'status' => 'ok',
            'message' => 'A list of breweries that are apart of the application',
            'breweries' => $breweries
        ]);
    }
}