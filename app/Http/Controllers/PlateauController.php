<?php

namespace App\Http\Controllers;

use App\Models\Plateau;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use mysql_xdevapi\Exception;

class PlateauController extends BaseController
{

    /**
     * List Plateau
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
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
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'xCoordinate' => 'numeric|min:32',
            'yCoordinate' => 'numeric|min:64'
        ]);

        $createRegionPlateau = json_encode(['x'=>$request->input('xCoordinate'),'y'=> $request->input('yCoordinate')]);

        $plateauRequest = new Plateau();
        $plateauRequest->name = $request->input('name');
        $plateauRequest->region= $createRegionPlateau;

        try {
            $plateauRequest->save();
            return response()->json($plateauRequest,200);
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }
}
