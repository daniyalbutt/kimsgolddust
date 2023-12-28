<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'discounts';

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
    protected $fillable = ['cat_id', 'date_range','date_start','type','discount_price','fixed_price'];
    

    
}
