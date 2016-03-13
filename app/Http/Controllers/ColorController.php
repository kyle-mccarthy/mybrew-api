<?php namespace App\Http\Controllers;

use App\Color;
use App\Beer;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Select all of the possible colors and return their information.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $colors = Color::all();
        return response(['colors' => $colors]);
    }

    /**
     * Get beers by their color values.  Can pass either start SRM value, start and end SRM values, or the color ID.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function beers(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $colorId = $request->get('color_id');

        if (!empty($start) && !empty($end)) {
            $beers = Beer::where('srm', '>=', $start)->where('srm', '<=', $end)->get();
            return response([
                'message' => 'Beers that fall in the SRM range of ' . $start . ' and ' . $end,
                'beers' => $beers,
            ]);
        } else if (!empty($start)) {
            $beers = Beer::where('srm', '=', $start)->get();
            return response([
                'message' => 'Beers that have the SRM value of ' . $start,
                'beers' => $beers,
            ]);
        } else if (!empty($colorId)) {
            $color = Color::find($colorId);
            if (!!$color) {
                $beers = Beer::where('srm', '>=', $color->start)->where('srm', '<=', $color->end)->get();
                return response([
                    'message' => 'Beers that are of the color ' . $color->name . ' (id: ' . $colorId . ')',
                    'beers' => $beers,
                ]);
            }
        }

        return response([
            'message' => 'Invalid data received.  You must provide the range of SRM values with the name start and end,' .
                'or a color ID must be provided.  To get a list of colors including their ID, name, start and end range ' .
                'perform a GET request on the color index route.',
            'errors' => 'Bad values received',
        ], 400);
    }
}