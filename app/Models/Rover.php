<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Rover extends Model
{
    protected $table = 'rover';

    public function plateau()
    {
        return $this->hasOne('App\Model\Plateau','id','plateauId');
    }
}
