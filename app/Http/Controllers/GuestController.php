<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\inquiry;

use App\newsletter;
use App\Program;
use App\imagetable;
use App\Product;
use App\Banner;
use DB;
use View;
use Session;
use App\Http\Traits\HelperTrait;

use App\orders;
use App\orders_products;

class GuestController extends Controller
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
		 $this->middleware('guest');
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
	
	public function signin()
    {
    	$page = DB::table('pages')->where('id', 11)->first();
		return view('account.signin', compact('page'));
		
	}
	
	public function signup()
    {
    	$page = DB::table('pages')->where('id', 12)->first();
		return view('account.signup', compact('page')); 
	}
	
	
}	
	