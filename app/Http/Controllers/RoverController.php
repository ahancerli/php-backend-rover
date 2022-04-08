<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class RoverController extends BaseController
{
    public function list()
    {
        return "get rover";
    }

    public function create()
    {
        return "post rover";
    }

    public function getState()
    {
        return "get state rover";
    }
    public function setState()
    {
        return "setState rover";
    }
}
