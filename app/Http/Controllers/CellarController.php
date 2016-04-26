<?php namespace App\Http\Controllers;

use App\Auth\StatelessGuard;
use Illuminate\Http\Request;
use Validator;
use App\History;
use App\Beer;

class CellarController extends Controller
{
    /**
     * Get the beer history of a user
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $history = $this->user->history()->with('beer', 'beer.style', 'beer.brewery')->get();
        return response([
            'status' => 'ok',
            'message' => 'The beer history of the user',
            'cellar' => $history,
        ]);
    }

    /**
     * Add a beer to a user's cellar with their rating and notes about the beer.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addBeer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'beer' => 'required|integer|exists:beers,id',
            'rating' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid data.',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        // see if a record for the user and beer already exists, we need to warn them to update instead of insert
        $check = History::where('user_id', '=', $this->user->id)->where('beer_id', '=', $request->get('beer'))->get();
        if (count($check) > 0) {
            return response([
                'status' => 'failed',
                'message' => 'This beer already exists in your history.  Did you mean to update the record?',
            ], 400);
        }

        $history = new History;
        $history->user_id = $this->user->id;
        $history->beer_id = $request->get('beer');
        $history->rating = (int)$request->get('rating');
        $history->notes = $request->get('notes');
        $history->save();

        return response([
            'status' => 'ok',
            'message' => 'Your beer rating has been added to your cellar.',
            'history' => $history,
        ]);
    }

    /**
     * Attempt to update a record for a user's beer rating.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateBeer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'beer' => 'required|integer|exists:beers,id',
            'rating' => 'integer|between:0,5',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid data.',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        // make sure that the record actually exists
        $history = History::where('user_id', '=', $this->user->id)->where('beer_id', '=', $request->get('beer'))->first();
        if (count($history) < 0) {
            return response([
                'status' => 'failed',
                'message' => 'This beer hasn\'t been rated by the user yet.  Did you mean to insert the record?',
            ], 400);
        }

        // update the records that had new information from the post data
        $rating = $request->get('rating');
        $notes = $request->get('notes');

        if (!is_null($rating)) {
            $history->rating = (int)$rating;
        }

        if (!!$notes) {
            $history->notes = $notes;
        }

        $history->save();

        return response([
            'status' => 'ok',
            'message' => 'The rating for the beer has been updated.',
            'history' => $history,
        ]);
    }

    /**
     * Remove the beer from a user's cellar
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroyBeer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'beer' => 'required|integer|exists:beers,id',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 'failed',
                'message' => 'Invalid data.',
                'errors' => $validator->errors()->all(),
            ], 400);
        }

        // make sure the user actually has the beer in their cellar before removing it
        $beer = History::where('user_id', '=', $this->user->id)->where('beer_id', '=', $request->get('beer'))->first();

        if (count($beer) == 0) {
            return response([
                'status' => 'failed',
                'message' => 'Beer does not exist in cellar',
            ], 400);
        }

        $beer->delete();

        return response([
            'status' => 'ok',
            'message' => 'The beer has been removed from the cellar',
        ]);
    }

    /**
     * Get the beer recommendations for a user.  Create a user's taste profile using the star rating system as a weight
     * and provide recommendations for beers that fall in a user's profile.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function recommend()
    {
        // get the history/past beers for a user
        $history = History::with('beer')->where('user_id', '=', $this->user->id)->get();

        // define the variables that serve as predictors
        $count = count($history);
        $starCount = 0;
        $srm = 0;
        $abv = 0;
        $ibu = 0;

        // ensure that the cellar has beers
        if ($count == 0) {
            return response([
                'status' => 'failed',
                'message' => 'To get a recommendation the user\'s cellar must contain beers.',
            ]);
        }

        // add variable to its total weighted value
        foreach ($history as $rating) {
            $beer = $rating->beer;
            $starCount += $rating->rating;
            $srm += $beer->srm * $rating->rating;
            $abv += $beer->abv * $rating->rating;
            $ibu += $beer->ibu * $rating->rating;
        }

        // determine the mean weight values
        $srm /= $starCount;
        $abv /= $starCount;
        $ibu /= $starCount;

        // exclude beers that are already in a users cellar
        $usersBeers = $this->user->history()->pluck('beer_id');

        // retrieve beers that fall within a certain range of the means
        $beers = Beer::where('srm', '>=', $srm - 1.5)->where('srm', '<=', $srm + 1.5)
            ->where('ibu', '>=', $ibu - 15)->where('ibu', '<=', $ibu + 15)
            ->where('abv', '>=', $abv - .75)->where('abv', '<=', $abv + .75)
            ->with('style')->whereNotIn('id', $usersBeers)->get();

        return response([
            'status' => 'ok',
            'message' => 'The following beers are in the user\'s recommendation profile',
            'profile' => [
                'srm' => $srm,
                'ibu' => $ibu,
                'abv' => $abv,
            ],
            'beers' => $beers,
        ]);
    }
}