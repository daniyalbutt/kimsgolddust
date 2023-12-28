<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
    protected $fillable = ['product_title', 'description', 'price', 'regular_price', 'sku', 'is_featured_home', 'tags', 'is_featured_menu', 'best_seller', 'short_description', 'category',  'stock', 'actual_price','discount_get_qty','discount_buy_qty', 'slug','theseTogether'];

    public function categorys()
    {
        $data = Category::select('id', 'name', 'parent')->whereIn('id', json_decode($this->category))->get();
        return $data;
    }

    public function category_list(){

        return $this->belongsToMany(Category::class, 'product_category', 'product_id');
    }

    public function attributes()
    {
        return $this->hasMany('App\ProductAttribute', 'product_id', 'id')->orderBy('order_id','asc');
    }

    public function attributesPrice()
    {
        $data = $this->hasOne('App\ProductAttribute', 'product_id', 'id')->orderby('id', 'asc')->first();

        if($data->price == 0){
            return $data->regular_price;
        }
        return $data->price;
    }

    public function getProductPrice(){
        $price = 0;
        if($this->price == 0){
            if($this->regular_price == 0){
                $price = $this->attributesPrice();
            }else{
                $price = $this->regular_price;
            }
        }else{
            $price = $this->price . ' <strike>$' . $this->regular_price. '</strike>';
        }
        return $price;
    }

    public function getMinPrice(){
        $price = [];
        $discount_price = [];
        $att = DB::table('product_attributes')->where('product_id', $this->id)->get();
        foreach($att as $key => $value){
            if($value->price == 0){
                array_push($price, $value->regular_price);
            }else{
                array_push($price, $value->price);
                array_push($discount_price, $value->regular_price);
            }
        }
        
        if(count($att) == 0){
            return '$'.$this->getProductPrice();
        }else{
            if($this->price != 0.00){
                return '$'.$this->getProductPrice();
            }
            if(min($price) == max($price)){
                if((count($discount_price) != 0) && ($discount_price[0] != 0)){
                    return '$'.min($price) . ' <strike>'.$discount_price[0].'</strike>';
                }else{
                    return '$'.min($price);
                }
            }else{
                return '$'.min($price) . ' – ' . '$'.max($price);
            }
        }
    }

}
