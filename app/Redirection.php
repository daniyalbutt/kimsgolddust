<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirection extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'redirections';

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
    protected $fillable = ['url', 'redirect_url'];

    
}