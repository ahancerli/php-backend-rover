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
        $roverRequest = new Rover();
        $roverRequest->name = $request->input('name');
        $roverRequest->plateauId = $request->input('plateauId');
        $roverRequest->location = $request->input('location');

        try {
            $roverRequest->save();
            return response()->json($roverRequest,200);
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }

    public function getState($id)
    {

    }

    public function setState(Request $request, $id)
    {
        $roverRequest = $this->getRover($id);

        if (!empty($roverRequest))
        {
            $commands = $request->input('commands');
            if (!empty($commands)) {
                $commandArray = $this->smashCommand($commands);
                if (count($commandArray) > 0) {
                    foreach ($commandArray as $command) {
                        if ($this->validCommand($command)) {
                           if($command == 'L' || $command == "R") {
                               $this->updateDirector($id, $command);
                           } elseif ($command == 'M') {
                               $this->editLocation($id);
                           }
                        } else {
                            return response()->json("$command komut okunamadı",404);
                        }
                    }
                } else {
                    return response()->json("Commend is Not Found",404);
                }

            } else {
                return response()->json("Commend is Not Found",404);
            }

        } else {
            return response()->json("Rover is Not Found",404);
        }
    }

    private function getRover($id)
    {
        try {
            return Rover::where('id', $id)->first();
        } catch (Exception $e) {
            return response()->json($e,500);
        }
    }

    private function smashCommand(string $commands): array
    {
        $commandsArray = [];
        $commandsArray = str_split($commands);
        return $commandsArray;
    }

    private function validCommand(string $commends): bool
    {
        if ($commends == "L" || $commends == "M" || $commends == "R") {
            return true;
        }
        return false;
    }

    private function updateDirector($id, $director)
    {
        $roverData = $this->getRover($id);
        $roverRequest = $roverData->getAttributes();

        if ($director == "L")
        {
            $this->findDirectorLeft($roverRequest);
        }
        if ($director == "R")
        {
            $this->findDirectorRight($roverRequest);
        }
    }

    private function editLocation($id)
    {
        $roverData = $this->getRover($id);
        $roverRequest = $roverData->getAttributes();
        $xValue = json_decode($roverRequest['location'])->x;
        $yValue = json_decode($roverRequest['location'])->y;

        if($roverRequest['direction'] == "E" || $roverRequest['direction'] == "W") {
            if ($roverRequest['direction'] == "E") {
                $this->CalculateXPlusCoordinate($roverRequest, $xValue);
            } elseif ($roverRequest['direction'] == "W") {
                $this->CalculateXMinorCoordinate($roverRequest, $xValue);
            }
        } elseif ($roverRequest['direction'] == "S" || $roverRequest['direction'] == "N") {
            if ($roverRequest['direction'] == "N") {
                $this->CalculateYPlusCoordinate($roverRequest, $yValue);
            } elseif ($roverRequest['direction'] == "S") {
                $this->CalculateYMinorCoordinate($roverRequest, $yValue);
            }
        }
    }

    private function updateLocation($data, $location)
    {
        try {
            Rover::where('id',$data['id'])->update(['location' => $location]);
        } catch (Exception $e) {
            response()->json($e, 500);
            return;
        }
    }

    private function findDirectorLeft($roverRequest)
    {
        switch ($roverRequest['direction']) {
            case 'N':
                $this->updateDirectorWest($roverRequest['id']);
                break;
            case 'S':
                $this->updateDirectorEast($roverRequest['id']);
                break;
            case 'E':
                $this->updateDirectorNorth($roverRequest['id']);
                break;
            case 'W':
                $this->updateDirectorSouth($roverRequest['id']);
                break;
        }
    }
    private function findDirectorRight($roverRequest)
    {
        switch ($roverRequest['direction']) {
            case 'N':
                $this->updateDirectorEast($roverRequest['id']);
                break;
            case 'S':
                $this->updateDirectorWest($roverRequest['id']);
                break;
            case 'E':
                $this->updateDirectorSouth($roverRequest['id']);
                break;
            case 'W':
                $this->updateDirectorNorth($roverRequest['id']);
                break;
        }
    }


    private function updateDirectorNorth($id)
    {
        try {
            Rover::where('id',$id)->update(['direction' => 'N']);
            return response()->json('Director Updated N',200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    private function updateDirectorSouth($id)
    {
        try {
            Rover::where('id',$id)->update(['direction' => 'S']);
            return response()->json('Director Updated S',200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    private function updateDirectorWest($id)
    {
        try {
            Rover::where('id',$id)->update(['direction' => 'W']);
            return response()->json('Director Updated W',200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    private function updateDirectorEast($id)
    {
        try {
            Rover::where('id',$id)->update(['direction' => 'E']);
            return response()->json('Director Updated E',200);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function CalculateXPlusCoordinate($data, $params)
    {
        if($params <  32) {
            $params = $params + 1;
            $location = ['x'=> $params, 'y'=>json_decode($data['location'])->y];
            $this->updateLocation($data,$location);
        } else {
            response()->json("You cannot enter a value greater than 32.", 500);
        }
    }

    public function CalculateXMinorCoordinate($data, $params)
    {
        if($params > 0) {
            $params = $params - 1;
            $location = ['x'=> $params, 'y'=>json_decode($data['location'])->y];
            $this->updateLocation($data,$location);
        } else {
            response()->json("cannot be less than 0", 500);
        }
    }

    public function CalculateYPlusCoordinate($data, $params)
    {
        if($params < 64) {
            $params = $params + 1;
            $location = ['x'=> json_decode($data['location'])->x, 'y'=>$params];
            $this->updateLocation($data,$location);
        } else {
            response()->json("You cannot enter a value greater than 64.", 500);
        }
    }

    public function CalculateYMinorCoordinate($data, $params)
    {
        if($params > 0) {
            $params = $params - 1;
            $location = ['x'=> json_decode($data['location'])->x, 'y'=>$params];
            $this->updateLocation($data,$location);
        } else {
            print_r('burasdas');
            response()->json("cannot be less than 0", 500);
        }
    }

}
