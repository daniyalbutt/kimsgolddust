<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\inquiry;
use App\schedule;
use App\newsletter;
use App\{post,Category};
use App\banner;
use App\imagetable;
use DB;
use Mail;
use View;
use Session;
use App\Http\Helpers\UserSystemInfoHelper;
use App\Http\Traits\HelperTrait;
use Auth;
use App\Profile;
use App\Page;
use App\Product;
use App\ProductAttribute;
use Image;
use Goutte\Client;
use Carbon\Carbon;

use App\Http\Traits;
class HomeController extends Controller
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

        $logo = imagetable::
                     select('img_path')
                     ->where('table_name','=','logo')
                     ->first();
             
        $favicon = imagetable::
                     select('img_path')
                     ->where('table_name','=','favicon')
                     ->first(); 
        
        View()->share('logo',$logo);
        View()->share('favicon',$favicon);

    } 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pro = DB::table('product_attributes')->select('product_id')->groupBy('product_id')->where('attribute_id', 18)->get();
        $page = DB::table('pages')->where('id', 1)->first();
        $section = DB::table('section')->where('page_id', 1)->get();
        $banners = DB::table('banners')->where('status',1)->orderBy('order_id', 'asc')->get();
        $testimonials = DB::table('testimonials')->where('show_on_home', 1)->get();
        $productvideos = DB::table('product_videos')->where('show_on_home', 1)->get();
        $categories = Category::where('show_on_home', 1)->where('status', 1)->get();
        $featured = Product::where('is_featured_home', 1)->whereHas('category_list', function ($q){
                        $q->where('status', 1);
                    })->get();
        return view('welcome', compact('page','banners', 'testimonials', 'section', 'productvideos', 'categories', 'featured'));
    }

    public function newFor(){
        $page = DB::table('pages')->where('id', 2)->first();
        $section = DB::table('section')->where('page_id', 2)->get();
        return view('newfor', compact('page', 'section'));
    }

    public function cutsomOrders(){
        $page = DB::table('pages')->where('id', 3)->first();
        $section = DB::table('section')->where('page_id', 3)->get();
        return view('customorder', compact('page', 'section')); 
    }

    public function testimonial(){
        $page = DB::table('pages')->where('id', 4)->first();
        $section = DB::table('section')->where('page_id', 4)->get();
        $testimonials = DB::table('testimonials')->get();
        return view('testimonial', compact('page', 'section', 'testimonials'));
    }

    public function ourStory(){
        $page = DB::table('pages')->where('id', 5)->first();
        $section = DB::table('section')->where('page_id', 5)->get();
        $productvideos = DB::table('product_videos')->where('show_on_home',1)->get();
        $stories = DB::table('stories')->get();
        return view('ourstory', compact('page', 'section', 'productvideos', 'stories'));
    }

     public function shippingTax(Request $request){
        $country = $request->country;
        $totalPrice = $request->totalPrice;
        $possibleId= array();
        $shipptax = array();
        $shippingRegion = DB::table('shippings')->get();
        foreach($shippingRegion as $item){
            $array = json_decode($item->region, true);
            if(in_array($country, $array)){
                $shipptax['zone_name'] = $item->zone_name;
                array_push($possibleId,$item->id);
            }else{

            }
        }
        $tax = DB::table('taxes')->whereIn('zone_id',$possibleId)->where('min','<=',$totalPrice)->where('max','>=',$totalPrice)->get();

        foreach($tax as $key => $value){
            $data = DB::table('shippings')->select('zone_name')->where('id', $value->zone_id)->first();
            $value->zone_name = $data->zone_name;
        } 
        
        if(count($tax) <= '1')
        {
            $shipptax['id'] = $tax[0]->id;
            $shipptax['min'] = $tax[0]->min;
            $shipptax['max'] = $tax[0]->max;
            $shipptax['row_cost'] = $tax[0]->row_cost;

            return response()->json(['shippingTax'=>$tax, 'status' => true]);
        }else{
            return response()->json(['shippingTax'=>$tax, 'status' => true]);
        }
    }

    public function schedule(){
        $page = DB::table('pages')->where('id', 6)->first();
        $section = DB::table('section')->where('page_id', 6)->get();
        $schedules = DB::table('schedules')->orderBy('order_id', 'desc')->get();
        return view('schedule', compact('page', 'section', 'schedules'));
    }

    public function contact(){
        $page = DB::table('pages')->where('id', 7)->first();
        $section = DB::table('section')->where('page_id', 7)->get();
        return view('contact', compact('page', 'section'));
    }

    public function faq(){
        $page = DB::table('pages')->where('id', 8)->first();
        $section = DB::table('section')->where('page_id', 8)->get();
        return view('faq', compact('page', 'section'));
    }

    public function privacyPolicy(){
        $page = DB::table('pages')->where('id', 9)->first();
        $section = DB::table('section')->where('page_id', 9)->get();
        return view('privacypolicy', compact('page', 'section'));
    }

    public function helpfulTips(){
        $page = DB::table('pages')->where('id', 10)->first();
        $section = DB::table('section')->where('page_id', 10)->get();
        return view('helpfultips', compact('page', 'section'));
    }

    public function firingProcess(){
        $page = DB::table('pages')->where('id', 13)->first();
        $section = DB::table('section')->where('page_id', 13)->get();
        $sliders = DB::table('firing_process_sliders')->get();
        $video =  DB::table('firing_process_videos')->get();
        return view('firingprocess', compact('page', 'section', 'sliders', 'video'));
    }

    public function jewelryCare(){
        $page = DB::table('pages')->where('id', 14)->first();
        $section = DB::table('section')->where('page_id', 14)->get();
        return view('jewelrycare', compact('page', 'section'));

    }

    public function searchProduct(Request $request){
        $keyword = $request->q;
        if($request->q == null){
            return redirect('/');
        }else{
            $cat = Product::where('product_title', 'LIKE', "%$keyword%")->orWhere('sku', 'LIKE', "%$keyword%")->whereHas('category_list', function ($q){
                        $q->where('status', 1);
                    })->get();
            return view('search', compact('cat', 'keyword'));
        }
    }
    
    public function getProductDetails(Request $request){
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        $short_description = str_replace(["\r\n", "\r", "\n"], "<span class='spacing'></span>", $product->short_description);
        $short_description = str_replace('\n', '', $short_description);
        $attribute_array = [];
        $att_model = ProductAttribute::groupBy('attribute_id')->where('product_id' , $product_id)->get();
        foreach($att_model as $att_models){
            $pro_att = \App\ProductAttribute::where(['attribute_id' => $att_models->attribute_id, 'product_id' => $product_id])->get();
            foreach($pro_att as $key => $pro_atts){
                $attribute_array[$att_models->attribute->name][$key]['id'] = $pro_atts->id;
                $attribute_array[$att_models->attribute->name][$key]['value'] = $pro_atts->value;
                $attribute_array[$att_models->attribute->name][$key]['qty'] = $pro_atts->qty;
                $attribute_array[$att_models->attribute->name][$key]['price'] = $pro_atts->price;
                $attribute_array[$att_models->attribute->name][$key]['image'] = $pro_atts->image;
                $attribute_array[$att_models->attribute->name][$key]['regular_price'] = $pro_atts->regular_price;
            }
        }
        $images = DB::table('product_imagess')->where('product_id', $product_id)->where('is_variant', 0)->get();
        $cat_list = [];
        foreach($product->category_list as $key => $cat){
            array_push($cat_list, $cat->name);
        }
        $total_price = $product->getMinPrice();
        return response()->json(['product'=> $product, 'status' => true, 'short_description' =>  \Illuminate\Support\Str::limit($short_description, 500, $end='...'), 'attribute_array' => $attribute_array, 'images' => $images, 'cat_list' => $cat_list, 'total_price' => $total_price]);
    }

 


    public function careerSubmit(Request $request)
    {
        inquiry::create($request->all());
        	$emailData = DB::table('thankyous')->first();
			$logo = imagetable::select('img_path')->where('table_name','=','logo')->first();
			$currentDate = Carbon::now()->format('d-M-Y');
			$data = [
                'details'=>[
                    'First_Name' =>$request->name,
                    'Email' =>$request->email,
                    'Phone'=>$request->number,
                    'heading'=>$emailData->heading,
                    'content'=>$emailData->content,
                    'Address'=>$request->address,
                    'zip'=>$request->zip,
                    'Country'=>$request->country,
                    'Request'=>$request->message,
                    'Logo'=>$logo->img_path,
                    'WebsiteName'=>config('services.website.name'),
                    'companyEmail'=>Traits\HelperTrait::returnFlag(218),
                    'companyPhone'=>Traits\HelperTrait::returnFlag(59),
                    'currentDate'=> $currentDate
                    
                ]    
            ];
           
            Mail::send('contactMail', $data, function($message) use ($data) {
    
                $message->from('tony@websitesvalley.com', "Kim's Gold Dust");
    
                $message->to($data['details']['Email'])->subject($data['details']['heading']);
            });
            Mail::send('contactMail', $data, function($message) use ($data) {
    
                $message->from('tony@websitesvalley.com', "Kim's Gold Dust");
    
                $message->to('tony@websitesvalley.com')->subject($data['details']['heading']);
            });
            // Email Template
        return response()->json(['message'=>'Thank you for contacting us. We will get back to you asap', 'status' => true]);
        return back();
    }

    public function newsletterSubmit(Request $request){

        $is_email = newsletter::where('newsletter_email',$request->newsletter_email)->count();
        if($is_email == 0) {        
            $inquiry = new newsletter;
            $inquiry->newsletter_email = $request->newsletter_email;
            $inquiry->save();
            
            $emailData = DB::table('newsletteremails')->first();
			$logo = imagetable::select('img_path')->where('table_name','=','logo')->first();
            $data = [
                'details'=>[
                    
                    'Email' =>$request->newsletter_email,
                    'heading'=>$emailData->heading,
                    'content'=>$emailData->content,
                    'Logo'=>$logo->img_path,
                    'WebsiteName'=>config('services.website.name'),
                    'companyEmail'=>Traits\HelperTrait::returnFlag(218),
                    'companyPhone'=>Traits\HelperTrait::returnFlag(59),
                    'currentDate'=> $inquiry->created_at->format('d-M-Y')
                    
                ]
                
            ];
           
            Mail::send('newsletterEmail', $data, function($message) use ($data) {
    
                $message->from('tony@websitesvalley.com', "Kim's Gold Dust");
    
                $message->to($data['details']['Email'])->subject($data['details']['heading']);
            });
            Mail::send('newsletterEmail', $data, function($message) use ($data) {
    
                $message->from('tony@websitesvalley.com', "Kim's Gold Dust");
    
                $message->to('tony@websitesvalley.com')->subject($data['details']['heading']);
            });
            // Email Template
            return response()->json(['message'=>'Thank you for contacting us. We will get back to you asap', 'status' => true]);
            
        }else{
            return response()->json(['message'=>'Email already exists', 'status' => false]);
        }
            
    }

    public function updateContent(Request $request){
        $id = $request->input('id');
        $keyword = $request->input('keyword');
        $htmlContent = $request->input('htmlContent');
        if($keyword == 'page'){
            $update = DB::table('pages')
                        ->where('id', $id)
                        ->update(array('content' => $htmlContent));

            if($update){
                return response()->json(['message'=>'Content Updated Successfully', 'status' => true]);
            }else{
                return response()->json(['message'=>'Error Occurred', 'status' => false]);
            }
        }else if($keyword == 'section'){
            $update = DB::table('section')
                        ->where('id', $id)
                        ->update(array('value' => $htmlContent));
            if($update){
                return response()->json(['message'=>'Content Updated Successfully', 'status' => true]);
            }else{
                return response()->json(['message'=>'Error Occurred', 'status' => false]);
            }
        }
    }
    
    public function checkTax(Request $request){
        $state = $request->state;
        $state_taxes = DB::table('state_taxes')->where('state_code', $request->state)->orWhere('state_name', $request->state)->first();
        if($state_taxes != null){
            return response()->json(['data' => $state_taxes, 'status' => true]);
        }else{
            return response()->json(['data' => $state_taxes, 'status' => false]);
        }
    }

}
