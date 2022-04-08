<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class PlateauController extends BaseController
{
    public function list()
    {
        return "get plato";
    }

    public function create()
    {
        return "post plato";
    }
}
