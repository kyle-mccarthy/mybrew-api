<?php namespace App\Http\Controllers;

use App\Brewery;

class BreweryController extends Controller
{

    public function index()
    {
        $breweries = Brewery::all();
        return response(['breweries' => $breweries]);
    }
}