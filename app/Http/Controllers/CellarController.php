<?php namespace App\Http\Controllers;

use App\Auth\StatelessGuard;

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

    public function index()
    {
        
    }
}