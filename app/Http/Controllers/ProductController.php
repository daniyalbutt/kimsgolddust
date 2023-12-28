<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\inquiry;

use App\newsletter;
use App\Program;
use App\imagetable;
use SoapClient;
use App\Product;
use App\Category;
use App\Banner;
use App\ProductAttribute;
use DB;
use View;
use Session;
use App\Http\Traits\HelperTrait;
use App\orders;
use App\orders_products;
use DateTime;
use App\wishlists;
use Auth;
use App\SaveLaterProduct;

class ProductController extends Controller
{
    use HelperTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // use Helper;

    public function __construct()
    {
        //$this->middleware('auth');
        $logo = imagetable::select('img_path')
            ->where('table_name', '=', 'logo')
            ->first();

        $favicon = imagetable::select('img_path')
            ->where('table_name', '=', 'favicon')
            ->first();

        View()->share('logo', $logo);
        View()->share('favicon', $favicon);
        //View()->share('config',$config);
    }

    public function index()
    {
        $products = new Product;
        if (isset($_GET['q']) && $_GET['q'] != '') {

            $keyword = $_GET['q'];

            $products = $products->where(function ($query)  use ($keyword) {
                $query->where('product_title', 'like', $keyword);
            });
        }
        $products = $products->select('id', 'product_title', 'price', 'image', 'regular_price', 'is_featured_home')->orderBy('id', 'asc')->get();
        return view('products', ['products' => $products]);
    }

    public function productDetail($id)
    {

        $product = new Product;
        $product_detail = $product->where('id', $id)->first();
        $products = DB::table('products')->get()->take(10);
        return view('product_detail', ['product_detail' => $product_detail, 'products' => $products]);
    }

    public function categoryDetail($slug, Request $request)
    {
        // dd($request->category);
        $array = [];
        
        $cat = DB::table('categories')->where('slug', $slug)->first();
       
        array_push($array, $cat->id);
        $main_parent = $this->getParent($cat->id);
        

        if ($request->has('min')) {
            $category = new Product();

            $category = $category->where([
                ['actual_price', '>=', $request->min],
                ['actual_price', '<=', $request->max],
            ]);

            if ($request->q != null) {
                $keyword = $request->q;
                
                $category = $category->where('product_title', 'LIKE', "%$keyword%")->orWhere('sku', 'LIKE', "%$keyword%");
            }

            if ($request->category != null) {
                $after_merge = [];
                foreach ($request->category as $key => $value) {
                    $list_cat = Category::find($value);
                    // $after_merge = array_merge($after_merge, $list_cat->getCategoryList());
                }
                $category = $category->whereHas('category_list', function ($q) use ($list_cat) {
                    $q->where('category_id', $list_cat->id);
                });
            }

            if ($request->tags != null) {
                $tags = $request->tags;
                $category = $category->where(function ($q) use ($tags) {
                    foreach ($tags as $value) {
                        $q->orWhere('tags', 'like', "%{$value}%");
                    }
                });
            }

            $category = $category->paginate(21);
        } else {
            $cat_id = $cat->id;
            $category = Product::whereHas('category_list', function ($q) use ($cat_id) {
                $q->where('category_id', $cat_id);
            })->orderBy('id', 'desc')->paginate(21);
           
        }
        
        $all_cat_sidebar = DB::table('categories')->whereIn('id', [$main_parent,2])->get();
        return view('shop.category_detail', compact('category', 'cat', 'all_cat_sidebar'));
    }
    
    public function getParent($id){
        $last_id = 0;
        $find_parent = $id;
        for($i = 0; $i < 5; $i++){
            if($find_parent != 0){
                $data = DB::table('categories')->where('id', $find_parent)->first();
                $find_parent = $data->parent;
                if($data->parent == 0){
                    $last_id = $data->id;
                }
                
            }
        }
        return $last_id;
    }


    public function cart()
    {
        $cartCount = 0;
        if(Session::get('cart') != null){
            $cartCount = COUNT(Session::get('cart'));
        }
        
        $language = Session::get('language');

        $product_detail = DB::table('products')->first();
        if (Auth::check()) {
            $savePr = DB::table('save_later_products')->where('user_id', Auth::user()->id)->get();

            $savePrd = array();
            foreach ($savePr as $key => $value) {
                $product = Product::where('id', $value->prod_id)->first();

                $savePrd[$product->id]['prd_id'] = $product->id;
                $savePrd[$product->id]['prd_name'] = $product->product_title;
                $savePrd[$product->id]['price'] = $product->getMinPrice();
                $savePrd[$product->id]['stock'] = $product->stock;
            }
        } else {
            $savePrd = Session::get('saveProd');
        }
        // 		dd($savePrd);
        if(Session::get('cart') != null){
            if (Session::get('cart') && count(Session::get('cart')) > 0 || Session::get('saveProd') && count(Session::get('saveProd')) > 0 || count($savePr) > 0) {
                // dd(Session::get('cart'));
                return view('shop.cart', ['cart' => Session::get('cart'), 'language' => $language, 'product_detail' => $product_detail, 'savePrd' => $savePrd]);
            }   
        }else {
            Session::flash('flash_message', 'No Product found');
            Session::flash('alert-class', 'alert-success');
            return redirect('/');
        }
    }

    public function discount(Request $request)
    {
        $code = $request->code;
        $data = DB::table('coupons')->where('code', $code)->where('status',1)->first();
        if ($data == null) {
            return response()->json(['message' => 'Invalid Coupon', 'status' => false]);
        } else {
            $past = new DateTime($data->expire);
            $now = new DateTime("now");
            if ($past < $now) {
                return response()->json(['message' => 'Invalid Coupon', 'status' => false]);
            }
            $cart = Session::get('cart');
            $total_price = 0;
            foreach ($cart as $key => $value) {
                if ($value['baseprice'] == 0) {
                    $total_price = ($total_price + $value['variation_price']) * $value['qty'];
                } else {
                    $total_price = ($total_price + $value['baseprice']) * $value['qty'];
                }
            }
            if ($data->type == 0) {
                $after_discount_price = $total_price - $data->price;
                if ($after_discount_price <= 0) {
                    return response()->json(['message' => 'Invalid Coupon', 'status' => false]);
                }
                $discount = ['price' => $data->price, 'total_price' => $total_price, 'after_discount_price' => $after_discount_price, 'code' => $data->code];
                Session::put('discount', $discount);
                return response()->json(['message' => 'Coupon Applied', 'status' => true, 'data' => $discount]);
            } else {
                $after_discount_price = $total_price - ($total_price * ($data->price / 100));
                $discount = ['price' => ($total_price * ($data->price / 100)), 'total_price' => $total_price, 'after_discount_price' => $after_discount_price, 'code' => $data->code];
                Session::put('discount', $discount);
                return response()->json(['message' => 'Coupon Applied', 'status' => true, 'data' => $discount]);
            }
        }
    }

    public function saveCart(Request $request)
    {
        $id = $_POST['product_id'];
        $wishlist = wishlists::where('product_id', $id)->where('user_id', Auth::user()->id)->first();
        if ($wishlist != '') {
            $wishlist->delete();
        }
        $var_item = $_POST['variation'];
        $result = array();
        $product_detail = DB::table('products')->where('id', $_POST['product_id'])->first();
        $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : '1';
        $cart = array();
        $cartId = $id;
        if ($request->variation != null) {
            foreach ($var_item as $key => $value) {
                $data = ProductAttribute::where('product_id', $_POST['product_id'])->where('value', $value)->first();
                $cartId = $cartId . '-' . $data->id;
            }
        }
        if (Session::has('cart')) {
            $cart = Session::get('cart');
        }
        $price = 0;
        if ($request->variation == null) {
            if ($product_detail->price == 0) {
                $price = $product_detail->regular_price;
            } else {
                $price = $product_detail->price;
            }
        }
        if ($id != "" && intval($qty) > 0) {
            if (array_key_exists($cartId, $cart)) {
                unset($cart[$cartId]);
            }
            $productFirstrow = Product::where('id', $id)->first();
            $productCat = DB::table('product_category')->where('product_id',$id)->get();
            // dump($productFirstrow);
            $catArray = [];
            foreach ($productCat as $key => $value) {
                $gat_cat = Category::where(['id' => $value->category_id])->first();
                $parent = $gat_cat->parent;
                while($parent != 0){
                    $gat_cat = \App\Category::where(['id' => $parent])->first();
                    $parent = $gat_cat->parent;
                    array_push($catArray, $parent);
                }
                array_push($catArray, $value->category_id);
            }
            
            // dump($catArray);
            $now = new \DateTime();
            $newDate = $now->format('Y-m-d');
            
            $discount = DB::table('discounts')->whereIn('cat_id',$catArray)
                        ->where('date_start', '<=', $newDate)
                        ->where('date_range','>=',$newDate)
                        ->orderBy('cat_id', 'desc')
                        ->first();
                        
            if($productFirstrow->sale == 1){
                if($discount != null){
                    if($discount->discount_price != '' ){
                        $discountPrice = $price - (($price * $discount->discount_price)/100);
                        $discountType = $discount->type;
                        $discountPercentage = $discount->discount_price;
                        
                    }else if($discount->fixed_price){
                        $discountPrice = $price - $discount->fixed_price ;
                        $discountType = $discount->type;
                        $discountPercentage = $discount->discount_price;
                    }
                }else{
                    $discountPrice = 0;
                    $discountType = 0;
                    $discountPercentage = 0;
                }
            }else{
                $discountPrice = 0;
                $discountType = 0;
                $discountPercentage = 0;
            }
            
            // dd($discount);
            $cart[$cartId]['cart_id'] = $cartId;
            $cart[$cartId]['id'] = $id;
            $cart[$cartId]['image'] = $productFirstrow->image;
            $cart[$cartId]['name'] = $productFirstrow->product_title;
            $cart[$cartId]['baseprice'] = $price;
            $cart[$cartId]['discountPrice'] = $discountPrice;
            $cart[$cartId]['discountType'] = $discountType;
            $cart[$cartId]['discountPercentage'] = $discountPercentage;
            $cart[$cartId]['qty'] = $qty;
            $cart[$cartId]['variation_price'] = 0;
            foreach ($var_item as $key => $value) {
                $data = ProductAttribute::where('product_id', $_POST['product_id'])->where('value', $value)->first();
                $cart[$cartId]['variation'][$data->id]['attribute'] = $data->attribute->name;
                $cart[$cartId]['variation'][$data->id]['attribute_val'] = $data->value;
                $cart[$cartId]['variation'][$data->id]['image'] = $data->image;
                if ($data->price == 0) {
                    $cart[$cartId]['variation'][$data->id]['attribute_price'] = $data->regular_price;
                    $cart[$cartId]['variation_price'] = $data->regular_price;
                    if($discount != ''){
                        if($discount->discount_price != '' ){
                            $cart[$cartId]['baseprice'] =  $data->regular_price;
                            $cart[$cartId]['discountPrice'] = $data->regular_price - (($data->regular_price * $discount->discount_price)/100);
                            $cart[$cartId]['discountType'] = $discount->type;
                            $cart[$cartId]['discountPercentage'] = $discount->discount_price;
                        }else if($discount->fixed_price){
                            $cart[$cartId]['baseprice'] =  $data->regular_price;
                            $cart[$cartId]['discountPrice'] = $data->regular_price - (($data->regular_price * $discount->discount_price)/100);
                            $cart[$cartId]['discountType'] = $discount->type;
                            $cart[$cartId]['discountPercentage'] = $discount->discount_price;
                        }                        
                    }
                } else {
                    $cart[$cartId]['variation'][$data->id]['attribute_price'] = $data->price;
                    $cart[$cartId]['variation_price'] = $data->price;
                    if($discount != ''){
                        if($discount->discount_price != '' ){
                          $cart[$cartId]['baseprice'] =  $data->price;
                            $cart[$cartId]['discountPrice'] = $data->regular_price - (($data->regular_price * $discount->discount_price)/100);
                            $cart[$cartId]['discountType'] = $discount->type;
                            $cart[$cartId]['discountPercentage'] = $discount->discount_price;
                            
                        }else if($discount->fixed_price){
                            $cart[$cartId]['baseprice'] =  $data->price;
                            $cart[$cartId]['discountPrice'] = $data->regular_price - (($data->regular_price * $discount->discount_price)/100);
                            $cart[$cartId]['discountType'] = $discount->type;
                            $cart[$cartId]['discountPercentage'] = $discount->discount_price;
                        }                        
                    }
                }
            }
            // dd($cart);
            Session::put('cart', $cart);
            Session::flash('message', 'Product Added to cart Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('/cart');
        } else {
            Session::flash('flash_message', 'Sorry! You can not proceed with 0 quantity');
            Session::flash('alert-class', 'alert-success');
            return back();
        }
    }

    public function updateCart(Request $request)
    {
        $cart = Session::get('cart');
        $qty = $request->qty;
        $pro_id = $_POST['product_id'];
        foreach ($pro_id as $key => $value) {
            $cart[$value]['qty'] = $qty[$key];
        }
        Session::put('cart', $cart);
        Session::flash('message', 'Your Cart Updated Successfully');
        Session::flash('alert-class', 'alert-success');
        return back();
    }


    public function removeCart($id)
    {
        if ($id != "") {
            if (Session::has('cart')) {
                $cart = Session::get('cart');
            }
            if (array_key_exists($id, $cart)) {
                unset($cart[$id]);
            }
            Session::put('cart', $cart);
        }
        Session::flash('message', 'Product item removed successfully');
        Session::flash('alert-class', 'alert-success');
        return back();
    }

    public function shop()
    {

        $shop = DB::table('products')->get();


        return view('shop.shop', compact('shop'));
    }

    public function shopDetail($slug)
    {
        $product = new Product;
        $product_detail = $product->where('slug', $slug)->first();
        $id = $product_detail->id;
        $array = [];
        foreach ($product_detail->category_list as $key => $value) {
            array_push($array, $value->id);
        }
        $titleWords = explode(' ', preg_replace('/\s+/', ' ', trim($product_detail->product_title)));
        // dd($product_detail->theseTogether);
        if($product_detail->theseTogether == null){
            $query = Product::query();
    
            foreach ($titleWords as $word) {
                $query->orWhere('product_title', 'LIKE', '%' . $word . '%');
            }
    
            $query->where('id', '!=', $product_detail->id);
    
            $shop = $query->take(4)->get();
    
            if (count($shop) < 4) {
                $innershop = Product::whereHas('category_list', function ($q) use ($array) {
                    $q->whereIn('category_id', $array);
                })->where('id', '!=', $id)->take(4 - count($shop))->get();
                $shop = $shop->concat($innershop);
            } 
        }
        else{
            // dd($product_detail->theseTogether);
            $relPrd = json_decode($product_detail->theseTogether);
            $prdArray = [];
            // dump($relPrd);
            foreach($relPrd as $key => $value){
                array_push($prdArray,$value);
            }
            $shop = Product::whereIn('id', $relPrd)->get();
            // dd($shop);
              
        }
        


        $att_model = ProductAttribute::groupBy('attribute_id')->where('product_id', $id)->get();
        $att_id = DB::table('product_attributes')->where('product_id', $id)->get();
        return view('shop.detail', compact('product_detail', 'shop', 'att_id', 'att_model'));
    }


    public function invoice($id)
    {

        $order_id = $id;
        $order = orders::where('id', $order_id)->first();
        $order_products = orders_products::where('orders_id', $order_id)->get();

        return view('account.invoice')->with('title', 'Invoice #' . $order_id)->with(compact('order', 'order_products'))->with('order_id', $order_id);;
    }

    public function checkout()
    {



        if (Session::get('cart') && count(Session::get('cart')) > 0) {
            $countries = DB::table('countries')
                ->get();
            return view('checkout', ['cart' => Session::get('cart'), 'countries' => $countries]);
        } else {
            Session::flash('flash_message', 'No Product found');
            Session::flash('alert-class', 'alert-success');
            return redirect('/');
        }
    }


    public function language()
    {
        $languages = $_POST['id'];

        Session::put('language', $languages);

        Session::put('is_select_dropdown', 1);
        // Session::put('language',$languages);
        // $test = Session::get('language');

        // Session::put('test',$test);

        //return redirect('shop-detail/1', ['test'=>$test]);
    }

    public function shipping(Request $request)
    {

        $PostalCode = $request->country; // Zipcode you are shipping To

        define("ENV", "demo"); // demo OR live

        if (ENV == 'demo') {
            $client = new SoapClient("https://staging.postaplus.net/APIService/PostaWebClient.svc?wsdl");
            $Password =  '123456';
            $ShipperAccount =  'DXB51487';
            $UserName =  'DXB51487';
            $CodeStation =  'DXB';
        } else {
            $client = new SoapClient("https://etrack.postaplus.net/APIService/PostaWebClient.svc?singleWsdl");
            $Password =  '';
            $ShipperAccount =  '';
            $UserName =  '';
            $CodeStation =  '';
        }

        $params = array(
            'ShipmentCostCalculation' => array(
                'CI' => array(
                    'Password' => $Password,
                    'ShipperAccount' => $ShipperAccount,
                    'UserName' => $UserName,
                    'CodeStation' => $CodeStation,
                ),
                "OriginCountryCode" => 'AE',
                "DestinationCountryCode" => $PostalCode,
                "RateSheetType" => 'DOC',
                "Width" => 1,
                "Height" => 1,
                "Length" => 1,
                "ScaleWeight" => 1,
            ),
        );


        try {

            $d = $client->__SoapCall("ShipmentCostCalculation", $params);
            $d = json_decode(json_encode($d), true);

            if (isset($d['ShipmentCostCalculationResult']['Message']) and ($d['ShipmentCostCalculationResult']['Message'] == 'SUCCESS')) {
                $status = true;
                $rate = $d['ShipmentCostCalculationResult']['Amount'];
            } else {
                $status = false;
                $messgae = $d['ShipmentCostCalculationResult']['Message'];
            }
        } catch (SoapFault $exception) {
            $status = false;
            $messgae = "Error Found Please try Again";
        }

        //if($status):
        //	echo $rate;
        //else:
        //	echo $messgae;
        //endif;

        //}
        //$cart = Session::get('cart');



        if ($status) {

            $cart = Session::get('cart');
            $cart['shipping'] = $rate;
            //$cart['shipping_address'] = $_POST['shipping_address'];
            Session::put('cart', $cart);

            // return view('shop.cart', ['rate'=> $rate, 'cart'=> $cart]);
            return redirect('/cart');
        } else {
            Session::flash('flash_message', 'Error');
            Session::flash('alert-class', 'alert-success');
            return view('shop.cart', ['messgae' => $messgae]);
        }
        return view('shop.cart', ['messgae' => $messgae, 'cart' => $cart]);
    }
}
