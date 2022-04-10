<?php

namespace App\Http\Controllers;

use App\Models\Plateau;
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
        $plateauRequest = new Plateau();
        $plateauRequest->name = $request->input('name');
        $plateauRequest->region= $request->input('region');

        try {
            $plateauRequest->save();
            return response()->json($plateauRequest,200);
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }
}
