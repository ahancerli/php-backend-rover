<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Plateau extends Model
{
    protected $table = 'plateau';

    protected $fillable = ['name','region'];

}
