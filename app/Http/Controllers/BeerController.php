<?php namespace App\Http\Controllers;

use App\Beer;
use App\DailyBeer;

class BeerController extends Controller
{
    /**
     * Get a list of all of the beers in the database
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $beers = Beer::with('brewery', 'style')->get();
        return response([
            'status' => 'ok',
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
            $beer->load('brewery', 'style');
            return response([
                'status' => 'ok',
                'message' => 'The beer with the id of ' . $id,
                'beer' => $beer,
            ]);
        }
        return response([
            'status' => 'failed',
            'error' => 'The beer with the id ' . $id . ' does not exist',
        ], 400);
    }

    /**
     * Get the random beer for the day.  If there isn't one that is the first API request to the route for the day
     * so randomly choose one and then store it.  Alternatively could use cron jobs.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function dailyBeer()
    {
        $daily = DailyBeer::with('beer')->where(\DB::raw('date(created_at)'), '=', [date('Y-m-d')])->first();

        // the beer does not exist for the current day, so select a random one and store it as the daily beer pick
        if (count($daily) == 0) {
            $random = Beer::all()->random(1);
            $daily = new DailyBeer();
            $daily->beer_id = $random->id;
            $daily->save();
        }

        $daily->beer->load('Brewery', 'Style');

        return response([
            'status' => 'ok',
            'message' => 'The beer of the day',
            'beer' => $daily->beer,
        ]);
    }

    public function quiz(Request $request)
    {
        // @todo beer suggestion based on user quiz
    }
}