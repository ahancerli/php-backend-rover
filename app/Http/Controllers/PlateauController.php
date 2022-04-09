<?php

namespace App\Http\Controllers;

use App\Models\Plateau;
use App\Models\Rover;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use mysql_xdevapi\Exception;

class PlateauController extends BaseController
{
    public function list()
    {
        try {
            $plateau =  Plateau::all();
            return response()->json($plateau,200);
        } catch(Exception $e) {
            return response()->json($e,500);
        }
    }


    /**
     * Create New Plateau
     *
     * @param  Request  $request
     */
    public function create(Request $request)
    {
        try
        {
            $plateau = Plateau::create($request->all());
            return response()->json($plateau,200);
        } catch (Exception $e) {
            return response()->json($e,500);
        }

    }
}
