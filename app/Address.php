<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $primaryKey='address_id';
    public $timestamps=false;
    protected $table='address';
}
