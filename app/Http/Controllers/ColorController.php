<?php namespace App\Http\Controllers;

use App\Color;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return response($colors);
    }
}