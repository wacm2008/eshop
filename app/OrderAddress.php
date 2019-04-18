<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
        //protected $primaryKey='order_id';
        public $timestamps=false;
        protected $table='order_address';
}
