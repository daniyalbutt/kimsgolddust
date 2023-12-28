<?php
namespace App\Http\Controllers;
use Helper;
use View;
use Illuminate\Http\Request;
use Cache;
use Session;
use App\products;
use App\wishlists;
use App\Product;
use App\imagetable;
use App\Http\Controllers\Controller;
use Auth;
use App\SaveLaterProduct;
use DB;
class SaveLaterController extends Controller
{
	 public function __construct()
    {
        //$this->middleware('auth');

        $logo = imagetable::
                     select('img_path')
                     ->where('table_name','=','logo')
                     ->first();
             
		$favicon = imagetable::
                     select('img_path')
                     ->where('table_name','=','favicon')
                     ->first();	

        //$profile = Profile::where('user_id', Auth::user()->id)->first();

		View()->share('logo',$logo);
		View()->share('favicon',$favicon);

    }
    public function index(Request $request, $id,$cartId){
        
    	if(Auth::check())
    	{   
    	    $userid = Auth::user()->id;
    	    $productId = $id;
    		$savPrd = new SaveLaterProduct;
    		$savPrd->user_id = Auth::user()->id;
    		$savPrd->prod_id = $id;
    // 		$savePrd->cart = 
           
			if(Session::has('cart')){
			    $cart = Session::get('cart');
    		}	
        	if(array_key_exists($cartId,$cart)){
        	    $savPrd->cart = json_encode($cart[$cartId]);
        	    
        	    
        	    unset($cart[$cartId]);
        	
        	}
        	$savPrd->save();
    		Session::put('cart',$cart);
	        Session::flash('message', 'Product Add In Save Later'); 
	        Session::flash('alert-class', 'alert-success');
			return back(); 
		}else
		    $product_title = Product::where('id',$id)->first();
		    if(Session::has('saveProd')){
				$savePrd = Session::get('saveProd');
			}
		    if(array_key_exists($cart,$savePrd)){
		       
	                Session::flash('message', 'Product Already Added In Save Later'); 
	                Session::flash('alert-class', 'alert-success');
					return back(); 
			}else{
			    if(Session::has('cart')){
				$cart = Session::get('cart');
    			}
    			
        			if(array_key_exists($cartId,$cart)){
        			    
        			    $cartPrd = json_encode($cart[$cartId]);
        			 //   $savPrd->cart = 
        					unset($cart[$cartId]);
        			} 
    			

    			
    			Session::put('cart',$cart);
    			
    			$savePrd = array();
    		    if(Session::has('saveProd')){
    				$savePrd = Session::get('saveProd');
    			}
    		    $savePrd[$id]['prd_id'] = $id;
    		    $savePrd[$id]['prd_name'] = $product_title->product_title;
    		    $savePrd[$id]['price'] = $product_title->getMinPrice();
    		    $savePrd[$id]['stock'] = $product_title->stock;
    		    $savePrd[$id]['cart'] = $cartPrd;
    		    Session::put('saveProd',$savePrd);
    		    
	            Session::flash('message', 'Product Add In Save Later'); 
	            Session::flash('alert-class', 'alert-success');
				return back();     		    
			}

			
	}
    
    public function delete(Request $request, $id){
        if(Auth::check()){
            $saveProduct=SaveLaterProduct::where('prod_id',$id)->first();
            $saveProduct->delete();
        }else{
            if($id!=""){
    			if(Session::has('saveProd')){
    				$savePrd = Session::get('saveProd');
    			}
    			if(array_key_exists($id,$savePrd)){
    				unset($savePrd[$id]);
    			}
    			Session::put('saveProd',$savePrd);
		    }
        }
		
		Session::flash('message', 'Save Product removed successfully'); 
		Session::flash('alert-class', 'alert-success');
		return back();	        
    }
    public function savePrdCart($id){
        // dd(Session::has('saveProd'));
        if($id!=""){
            
                if (Session::has('cart')) {
                    $cart = Session::get('cart');
                }else{
                    $cart = array(); 
                }
            if(Auth::check()){
                $savePrd = DB::table('save_later_products')->where('user_id',Auth::user()->id)->where('prod_id',$id)->first();
                $savePrdCart = json_decode($savePrd->cart);
               
    			    $cartId = $savePrd->prod_id;
    			    
    			    $cart[$cartId]['cart_id'] = $savePrdCart->cart_id;
                    $cart[$cartId]['id'] = $savePrdCart->id;
                    $cart[$cartId]['image'] = $savePrdCart->image;
                    $cart[$cartId]['name'] = $savePrdCart->name;
                    $cart[$cartId]['baseprice'] = $savePrdCart->baseprice;
                    $cart[$cartId]['qty'] =  $savePrdCart->qty;
                    $cart[$cartId]['variation_price'] = $savePrdCart->variation_price;
                    foreach($savePrdCart->variation as $key => $value)
                    {
                        
                        $cart[$cartId]['variation'][$key]['attribute'] = $value->attribute;
                        $cart[$cartId]['variation'][$key]['attribute_val'] = $value->attribute_val;
                        $cart[$cartId]['variation'][$key]['image'] = $value->image;
                        $cart[$cartId]['variation'][$key]['attribute_price'] = $value->attribute_price;
                       
                    }
                    DB::table('save_later_products')->where('prod_id',$id)->where('user_id',Auth::user()->id)->delete();
                    Session::put('cart', $cart);
                    
            }else{
                if(Session::has('saveProd')){
    				$savePrd = Session::get('saveProd');
    			
    			}
    			if(array_key_exists($id,$savePrd)){
    			    $savePrdCart = json_decode($savePrd[$id]['cart']);

    			    $cartId = $savePrdCart->cart_id;
    			 //   dd($cartId);
    			    $cart[$cartId]['cart_id'] = $cartId;
                    $cart[$cartId]['id'] = $savePrdCart->id;
                    $cart[$cartId]['image'] = $savePrdCart->image;
                    $cart[$cartId]['name'] = $savePrdCart->name;
                    $cart[$cartId]['baseprice'] = $savePrdCart->baseprice;
                    $cart[$cartId]['qty'] =  $savePrdCart->qty;
                    $cart[$cartId]['variation_price'] = $savePrdCart->variation_price;
                    foreach($savePrdCart->variation as $key => $value)
                    {
                        
                        $cart[$cartId]['variation'][$key]['attribute'] = $value->attribute;
                        $cart[$cartId]['variation'][$key]['attribute_val'] = $value->attribute_val;
                        $cart[$cartId]['variation'][$key]['image'] = $value->image;
                        $cart[$cartId]['variation'][$key]['attribute_price'] = $value->attribute_price;
                       
                    }
    				unset($savePrd[$id]);
    			}
    			Session::put('saveProd',$savePrd);
    			Session::put('cart', $cart);
            }
    		
                Session::flash('message', 'Product Added to cart Successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect('/cart');
		    }
    }
}