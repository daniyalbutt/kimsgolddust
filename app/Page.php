<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

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
    protected $fillable = ['page_name', 'name', 'content', 'image', 'seo_title', 'seo_description', 'seo_keyword', 'additional_seo', 'banner_color', 'left_image' , 'right_image','image_alter_tag','right_image_alter_tag','left_image_alter_tag','og_description','og_title'];

    public function sections()
    {
        return $this->hasMany('App\Section');
    }
}
