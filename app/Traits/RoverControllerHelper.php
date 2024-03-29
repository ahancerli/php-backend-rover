<?php

namespace App\Traits;

use App\Models\Rover;
use mysql_xdevapi\Exception;

trait RoverControllerHelper
{
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
            return $this->findDirectorLeft($roverRequest);
        }
        if ($director == "R")
        {
            return $this->findDirectorRight($roverRequest);
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
                $returnData = $this->CalculateXPlusCoordinate($roverRequest, $xValue);
                if ($returnData)
                    return response()->json($returnData->getContent(), 500);
            } elseif ($roverRequest['direction'] == "W") {
                $returnData = $this->CalculateXMinorCoordinate($roverRequest, $xValue);
                if ($returnData)
                    return response()->json($returnData->getContent(), 500);
            }
        } elseif ($roverRequest['direction'] == "S" || $roverRequest['direction'] == "N") {
            if ($roverRequest['direction'] == "N") {
                $returnData = $this->CalculateYPlusCoordinate($roverRequest, $yValue);
                if ($returnData)
                    return response()->json($returnData->getContent(), 500);
            } elseif ($roverRequest['direction'] == "S") {
                $returnData = $this->CalculateYMinorCoordinate($roverRequest, $yValue);
                if ($returnData)
                    return response()->json($returnData->getContent(), 500);
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
                return $this->updateDirectors($roverRequest['id'], 'W');
            case 'S':
                return $this->updateDirectors($roverRequest['id'], 'E');
            case 'E':
                return $this->updateDirectors($roverRequest['id'], 'N');
            case 'W':
                return $this->updateDirectors($roverRequest['id'], 'S');
        }
    }

    private function findDirectorRight($roverRequest)
    {
        switch ($roverRequest['direction']) {
            case 'N':
                return $this->updateDirectors($roverRequest['id'], 'E');
            case 'S':
                return $this->updateDirectors($roverRequest['id'], 'W');
            case 'E':
                return$this->updateDirectors($roverRequest['id'], 'S');
            case 'W':
                return $this->updateDirectors($roverRequest['id'], 'N');
        }
    }

    private function updateDirectors($id, $paramDirector)
    {
        try {
            Rover::where('id',$id)->update(['direction' => $paramDirector]);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    private function CalculateXPlusCoordinate($data, $params)
    {
        if($params <  32) {
            $params = $params + 1;
            $location = ['x'=> $params, 'y'=>json_decode($data['location'])->y];
            $this->updateLocation($data,$location);
        } else {
            return response()->json("x coordinte You cannot enter a value greater than 32.", 500);
        }
    }

    private function CalculateXMinorCoordinate($data, $params)
    {
        if($params > 0) {
            $params = $params - 1;
            $location = ['x'=> $params, 'y'=>json_decode($data['location'])->y];
            $this->updateLocation($data,$location);
        } else {
            return response()->json("x coordinte cannot be less than 0", 500);
        }
    }

    private function CalculateYPlusCoordinate($data, $params)
    {
        if($params < 64) {
            $params = $params + 1;
            $location = ['x'=> json_decode($data['location'])->x, 'y'=>$params];
            $this->updateLocation($data,$location);
        } else {
            return response()->json("y coordinte You cannot enter a value greater than 64.", 500);
        }
    }

    private function CalculateYMinorCoordinate($data, $params)
    {
        if($params > 0) {
            $params = $params - 1;
            $location = ['x'=> json_decode($data['location'])->x, 'y'=>$params];
            $this->updateLocation($data,$location);
        } else {
            return response()->json("y coordinte cannot be less than 0", 500);
        }
    }

    private function smashCommand(string $commands): array
    {
        return str_split($commands);
    }

}
