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

    /**
     * Get a list of the beers offered by a brewery
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function beers($id)
    {
        $brewery = Brewery::find($id);

        if (count($brewery) < 1) {
            return response([
                'status' => 'failed',
                'message' => 'Brewery with id ' . $id . ' not found',
            ], 404);
        }

        $brewery->load('beers');

        return response([
            'status' => 'ok',
            'message' => 'List of beers for the brewery',
            'brewery' => $brewery,
        ]);
    }
}