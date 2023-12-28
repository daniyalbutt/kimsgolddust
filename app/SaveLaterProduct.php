<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaveLaterProduct extends Model
{
    protected $table = 'save_later_products';
    
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    
    protected $fillable = ['user_id', 'prod_id','cart'];
}