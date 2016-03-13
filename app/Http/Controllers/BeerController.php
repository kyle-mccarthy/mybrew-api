<?php namespace App\Http\Controllers;

use App\Beer;

class BeerController extends Controller
{
    /**
     * Get a list of all of the beers in the database
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $beers = Beer::with('brewery')->get();
        return response([
            'message' => 'Index of all the beer data stored in the database',
            'copyright' => 'All information aggregated is information obtained from the brewer\'s website',
            'beers' => $beers
        ]);
    }

    /**
     * Select a specific beer based on its ID
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function beer($id)
    {
        $beer = Beer::find($id);
        if (!!$beer) {
            return response([
                'message' => 'The beer with the id of ' . $id,
                'beer' => $beer,
            ]);
        }
        return response([
            'error' => 'The beer with the id ' . $id . ' does not exist',
        ], 400);
    }
}