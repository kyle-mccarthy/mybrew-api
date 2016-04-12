<?php namespace App\Http\Controllers;

use App\Beer;
use App\DailyBeer;
use \Validator;
use Illuminate\Http\Request;

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

    /**
     * @param Request $request
     * @return mixed
     */
    public function quiz(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keywords' => 'string',
            'fruits' => 'array',
            'aroma' => 'boolean',
            'flavors' => 'array',
            'bitterness' => 'integer',
            'color' => 'integer',
            'maltiness' => 'boolean',
        ]);

        // one of the required post parameters was not include, error
        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'The request did not contain all of the answers',
                'errors' => $validator->errors(),
            ], 400);
        }

        // collect the keywords that will be searched for
        $keywords = [];
        $ibu = null;
        $abv = null;
        $srm = null;

        // biggest appeal keyword
        if ($request->has('keywords')) {
            $keyword = $request->get('keywords');
            $keyword = strtok($keyword, " ");
            array_push($keywords, $keyword);
        }

        // fruit keywords
        if ($request->has('fruits')) {
            foreach($request->get('fruits') as $fruit) {
                $fruit = strtolower($fruit);
                if ($fruit == "berries") {
                    array_push($keywords, "cherry");
                    array_push($keywords, "raspberry");
                    array_push($keywords, "strawberry");
                } else {
                    array_push($keywords, $fruit);
                }
            }
        }

        // aroma keywords
        if ($request->has('aroma')) {
            if ($request->get('aroma')) {
                array_push($keywords, "pine");
                array_push($keywords, "ginger");
                array_push($keywords, "oak");
            }
        }

        // flavor undertones keywords
        if ($request->has('flavors')) {
            foreach($request->get('flavors') as $flavor) {
                array_push($keywords, $flavor);
            }
        }

        // get the extent of bitterness that they like
        if ($request->has('bitterness')) {
            $ibu = [];
            $bitterness = $request->get('bitterness');
            if ($bitterness == 1) {
                $ibu = [0, 33];
            } else if ($bitterness == 2) {
                $ibu = [33, 66];
            } else if ($bitterness == 3) {
                $ibu = [66, 200];
            }
        }

        if ($request->has('color')) {
            $color = $request->get('color');
            if ($color == 1) {
                $srm = [0, 14];
            } else if ($color == 2) {
                $srm = [14, 20];
            } else if ($color == 3) {
                $srm = [20, 100];
            }
        }

        // do they like malti (high abv) beers
        if ($request->has('maltiness')) {
            if ($request->get('maltiness')) {
                $abv = [6, 15];
            }
        }

        // $beers = Beer::whereHas('keywords', function($query) {
        //
        // })->get();


        // return a successful response with the beers chosen
        return response([
            'status' => 'ok',
            'message' => 'The following beers were selected',
            'beers' => [],
            'profile' => [
                'keywords' => $keywords,
                'ibu' => $ibu,
                'srm' => $srm,
                'abv' => $abv,
            ]
        ]);
    }
}