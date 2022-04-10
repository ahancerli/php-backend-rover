<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Rover extends Model
{
    protected $table = 'rover';
    protected $fillable = ['name','plateauId','director','location'];

    public function plateau()
    {
        return $this->hasOne(Plateau::class,'plateauId','id');
    }
}
