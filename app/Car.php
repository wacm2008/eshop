<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $primaryKey='car_id';
    public $timestamps=false;
    protected $table='car';
}
