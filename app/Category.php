<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class Category extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

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
    protected $fillable = ['name', 'slug', 'menu_image', 'show_on_home', 'parent', 'image', 'seo_title', 'seo_description', 'seo_keyword', 'additional_seo'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent');
    }

    public function childrenCategories(){
        return $this->hasMany(Category::class, 'parent')->with('categories');
    }

    public function children(){
        return $this->hasMany(Category::class, 'parent')->with('children');
    }

    function generateCategories(){
        foreach ($categories as $category) {
            dump($category);
            echo '<li>' . $category->name . '</li>';
            if (count($category->children) > 0) {
                echo '<ul>';
                    echo '<li>';
                        generateCategories($category->children);
                    echo '</li>';
                echo '</ul>';
            }
        }
    }

    public function getparent(){
        if($this->parent != 0){
            $data = DB::table('categories')->where('id', $this->parent)->first();
            return $data->name . ' > ';
        }
        return '';
    }

    public function getProductCount(){
        $product_count = 0;
        $array = [];
        array_push($array, $this->id);
        $data = DB::table('categories')->select('id')->where('parent', $this->id)->get()->toArray();
        if(count($data) != 0){
            foreach ($data as $key => $value) {
                array_push($array, $value->id);
                $inner_data = DB::table('categories')->select('id')->where('parent', $value->id)->get()->toArray();
                if(count($inner_data) != 0){
                    foreach ($inner_data as $innerkey => $innervalue) {
                        array_push($array, $innervalue->id);
                        $sub_inner_data = DB::table('categories')->select('id')->where('parent', $innervalue->id)->get()->toArray();
                        if(count($sub_inner_data) != 0){
                            foreach ($sub_inner_data as $subinnerkey => $subinnervalue) {
                                array_push($array, $subinnervalue->id);
                            }
                        }
                    }
                }
            }
        }

        $product_count = Product::whereHas('category_list', function($q) use ($array){
            $q->whereIn('category_id', $array);
        })->count();

        return $product_count;
    }

    public function getCategoryList(){
        $array = [];
        array_push($array, $this->id);
        $data = DB::table('categories')->select('id')->where('parent', $this->id)->get()->toArray();
        if(count($data) != 0){
            foreach ($data as $key => $value) {
                array_push($array, $value->id);
                $inner_data = DB::table('categories')->select('id')->where('parent', $value->id)->get()->toArray();
                if(count($inner_data) != 0){
                    foreach ($inner_data as $innerkey => $innervalue) {
                        array_push($array, $innervalue->id);
                        $sub_inner_data = DB::table('categories')->select('id')->where('parent', $innervalue->id)->get()->toArray();
                        if(count($sub_inner_data) != 0){
                            foreach ($sub_inner_data as $subinnerkey => $subinnervalue) {
                                array_push($array, $subinnervalue->id);
                            }
                        }
                    }
                }
            }
        }
        return $array;
    }
    
    public function has_discount(){
        return $this->hasOne(Models\Discount::class, 'cat_id', 'id')->where('date_start', '<=', Carbon::now())->where('date_range', '>=', Carbon::now());
    }


}
