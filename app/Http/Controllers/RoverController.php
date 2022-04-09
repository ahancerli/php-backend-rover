<?php

namespace App\Http\Controllers;

use App\Models\Rover;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use mysql_xdevapi\Exception;
use function PHPUnit\Framework\throwException;

class RoverController extends BaseController
{
    public function list()
    {
        try {
            $rover =  Rover::all();
            return response()->json($rover,200);
        } catch(Exception $e) {
            return response()->json($e,500);
        }
    }

    public function create(Request $request)
    {
        print_r($request->post('categoryId'));
        die("asd");


        $categoryId = $request->input('categoryId');


        try
        {
            $rover = Rover::create($request->all());
            return response()->json($rover,200);
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }

    public function getState($id)
    {
        return "get state rover";
    }

    public function setState(Request $request, $id)
    {

    }

    private function getRover($id)
    {
        return Rover::where('id', $id)->first();
    }
}
