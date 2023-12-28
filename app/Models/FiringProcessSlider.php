<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiringProcessSlider extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'firing_process_sliders';

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
    protected $fillable = ['image', 'title', 'content'];

    
}
