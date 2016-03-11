<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
<<<<<<< HEAD
=======
use App\Auth\StatelessGuard;
>>>>>>> 0b925e97878510ba1e5086461f2121bab40c6c91

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
<<<<<<< HEAD
=======

    public function __construct(StatelessGuard $statelessGuard)
    {
        $this->auth = $statelessGuard;
    }
>>>>>>> 0b925e97878510ba1e5086461f2121bab40c6c91
}
