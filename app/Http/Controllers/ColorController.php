<?php namespace App\Http\Controllers;

use App\Color;

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
}