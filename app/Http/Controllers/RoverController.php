<?php

namespace App\Http\Controllers;

use App\Models\Plateau;
use App\Models\Rover;
use App\Traits\RoverControllerHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use mysql_xdevapi\Exception;

class RoverController extends BaseController
{

    use RoverControllerHelper;

    /**
     * List Plateau
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        try {
            $rover =  Rover::all();
            return response()->json($rover,200);
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
            'plateauId' => 'required|numeric',
            'xCoordinate' => 'numeric|max:32',
            'yCoordinate' => 'numeric|max:64',
        ]);

        $createLocationRover = json_encode(['x'=>(int)$request->input('xCoordinate'),'y'=> (int)$request->input('yCoordinate')]);
        $plateu = Plateau::findOrFail($request->input('plateauId'));

        if ($plateu)
            $plateuId = $request->input('plateauId');
        else
            return response()->json("Plato is Not Found",500);


        $roverRequest = new Rover();
        $roverRequest->name = $request->input('name');
        $roverRequest->plateauId = $plateuId;
        $roverRequest->location = $createLocationRover;

        try {
            $roverRequest->save();
            return response()->json($roverRequest,200);
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }

    /**
     * Create New Plateau
     *
     * @param $id
     * @return JsonResponse
     */

    /**
     * Create New Plateau
     *
     * @param $id
     * @return JsonResponse
     */
    public function getState($id): JsonResponse
    {
        try {
            $roverData = $this->getRover($id)->getAttributes();
            $name = $roverData['name'];
            $location = $roverData['location'];
            $direction = $roverData['direction'];
        } catch (Exception $e) {
            return response()->json($e,500);
        }

        try {
            $plateuData = Plateau::findOrFail($roverData['plateauId'])->getAttributes();
            $plateauName = $plateuData['name'];
        } catch (Exception $e) {
            return response()->json($e,500);
        }

        return response()->json("$name Rover'ı $plateauName Platosunda  $direction yönünde $location kordinantlarında yer almaktadır" ,200);
    }

    /**
     * Create New Plateau
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function setState(Request $request, $id): JsonResponse
    {
        $roverRequest = $this->getRover($id);
        if (!empty($roverRequest))
        {
            if ($request->has('commands')) {
                $commands = $request->input('commands');
                $commandArray = $this->smashCommand($commands);
                if (count($commandArray) > 0) {
                    foreach ($commandArray as $command) {
                        if ($this->validCommand($command)) {
                           if($command == 'L' || $command == "R") {
                               try {
                                   $returnData = $this->updateDirector($id, $command);
                                   if ($returnData) {
                                       return response()->json($returnData, $returnData->getStatusCode());
                                   }
                               } catch (Exception $e) {
                                   response()->json($e, 500);
                               }

                           } elseif ($command == 'M') {
                               try {
                                  $returnData =  $this->editLocation($id);
                                  if ($returnData) {
                                      return response()->json($returnData, $returnData->getStatusCode());
                                  }
                               }catch (Exception $e) {
                                   response()->json($e, 500);
                               }
                           }
                        } else {
                            return response()->json("$command komut okunamadı",404);
                        }
                    }

                } else {
                    return response()->json("Command is Not Found",404);
                }

            } else {
                return response()->json("Command is Not Found",404);
            }

        } else {
            return response()->json("Rover is Not Found",404);
        }
        return response()->json("Rover Moved in Designated Directions",200);
    }

    /**
     * Create New Plateau
     *
     * @param $id
     */
    public function getRover($id)
    {
        try {
            return Rover::where('id', $id)->first();
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }
}
