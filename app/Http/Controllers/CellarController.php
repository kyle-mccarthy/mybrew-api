<?php namespace App\Http\Controllers;

use App\Auth\StatelessGuard;
use Illuminate\Http\Request;
use Validator;
use App\History;

class CellarController extends Controller
{
    /**
     * CellarController constructor. Injects the StatelessGuard dependency into the controller.  Also sets the user for
     * the current request as an attribute of the controller.
     *
     * @param StatelessGuard $statelessGuard
     */
    public function __construct(StatelessGuard $statelessGuard)
    {
        parent::__construct($statelessGuard);
        $this->user = $this->auth->user();
    }

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
}