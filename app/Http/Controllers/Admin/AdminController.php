<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\imagetable;
use Auth;
use App\inquiry;
use App\Models\ProductVideo;
use DB;
use Image;
use File;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */

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
					 
	    $footer_logo = imagetable::
					 select('img_path')
					 ->where('table_name','=','footer_logo')
					 ->first();

		View()->share('logo',$logo);
		View()->share('favicon',$favicon);
		View()->share('footer_logo',$footer_logo);
		

		
	}
	
    public function index()
    {
        return view('auth.login')->with('title','Josue Francois');;
    } 
	
	public function dashboard()
    {   
      
        $prdSale = \DB::table('orders_products')
                    ->select('order_products_product_id as List', \DB::raw('COUNT(order_products_qty) as total_count'), 'order_products_product_id')
                    ->groupBy('order_products_product_id')
                    ->get();
        $prdPofit = \DB::table('orders_products')->get();
		$totalOrder = \DB::table('orders')->count();

        $totalPrd = \DB::table('products')->get();
        $user = \DB::table('users')->where('id','!=',1)->get();            
        $orders = DB::table('orders')->get();
        $orderTotal= 0;
        $orderTotalPrice = 0;
        foreach($prdSale as $key => $value){
            $orderTotal+=$value->total_count;
        }
        foreach($prdPofit as $key => $item){
            $orderTotalPrice += $item->order_products_price;
        }
        //  dd($orderTotalPrice);
        return view('admin.dashboard.index')->with(compact('orderTotal'))->with(compact('orderTotalPrice'))->with(compact('user'))->with(compact('orders'))->with(compact('totalPrd'))->with(compact('totalOrder'));
    } 
	

    public function configSettingUpdate(Request $request)
    {
    	foreach($request->all() as $k => $v){
    		if($k == '_token'){
    			continue;
    		}
    		if ($request->hasFile('image')) {
            
	            $category = category::where('id', $id)->first();
	            $image_path = public_path($category->image); 
	            
	            if(File::exists($image_path)) {
	                File::delete($image_path);
	            }

	            $file = $request->file('image');
	            $fileNameExt = $request->file('image')->getClientOriginalName();
	            $fileNameForm = str_replace(' ', '_', $fileNameExt);
	            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
	            $fileExt = $request->file('image')->getClientOriginalExtension();
	            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
	            $pathToStore = public_path('uploads/categorys/');
	            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

	             $requestData['image'] = 'uploads/categorys/'.$fileNameToStore;               
	        }

    		if($request->hasFile($k)){
    			$data = DB::table('m_flag')->where('flag_type', $k)->first();
	            $image_path = public_path($data->flag_value);
	            if(File::exists($image_path)) {
	                File::delete($image_path);
	            }
	            $file = $request->file($k);
	            $fileNameExt = $file->getClientOriginalName();
	            $fileNameForm = str_replace(' ', '_', $fileNameExt);
	            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
	            $fileExt = $file->getClientOriginalExtension();
	            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
	            $pathToStore = public_path('uploads/config/');
	            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);
	            DB::table('m_flag')->where('flag_type', $k)->update([
	    			'flag_value' => 'uploads/config/'.$fileNameToStore
	    		]);
    		}else{
    			DB::table('m_flag')->where('flag_type', $k)->update([
	    			'flag_value' => $v
	    		]);
    		}
    	}
    	
        // if(isset($_POST)) {
        //     foreach($_POST as $key=>$value) {
        //         if($key=='_token') {
        //             continue;
        //         }
        //         DB::UPDATE("UPDATE m_flag set flag_value = '".$value."',flag_additionalText = '".$value."' where flag_type = '".$key."'");	

               
        //     }
        // }
		session()->flash('message', 'Successfully Updated');
        return redirect('admin/config/setting');
        
    }
	
	public function faviconEdit() {
		
		$user = Auth::user();
		$favicon = DB::table('imagetable')->where('table_name', 'favicon')->first();
		
		return view('admin.dashboard.index-favicon')->with(compact('favicon'))->with('title',$user->name.' Edit Favicon');
		
    }

	public function faviconUpload(Request $request) {
			
			$validArr = array();
			if($request->file('image')) {
				$validArr['image'] = 'required|mimes:jpeg,jpg,png,gif|required|max:10000';
			}	
		
			$this->validate($request, $validArr);
		
			$requestData = $request->all();
			$imagetable = imagetable::where('table_name', 'favicon')->first();
			
			if(count($imagetable) == 0) {
				
				$file = $request->file('image');
			
                $destination_path = public_path('uploads/imagetable/');
                $profileImage = date("Ymd").".".$file->getClientOriginalExtension();

                Image::make($file)->resize(16, 16)->save($destination_path . DIRECTORY_SEPARATOR. $profileImage);

				$image = new imagetable;				
                $image->img_path = 'uploads/imagetable/'.$profileImage;
				$image->table_name = 'favicon';
                $image->save();
				
				
			} else {
				
				if ($request->hasFile('image')) {
					$image_path = public_path($imagetable->img_path);
					
					if(File::exists($image_path)) {
						File::delete($image_path);
					}
				
					$file = $request->file('image');
					$fileNameExt = $request->file('image')->getClientOriginalName();
					$fileNameForm = str_replace(' ', '_', $fileNameExt);
					$fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
					$fileExt = $request->file('image')->getClientOriginalExtension();
					$fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
					
					
					$pathToStore = public_path('uploads/imagetable/');
					Image::make($file)->resize(16, 16)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

				
					imagetable::where('table_name', 'favicon')
							->update(['img_path' => 'uploads/imagetable/'.$fileNameToStore]);
					
				}
				
			}

			session()->flash('message', 'Successfully updated the favicon');
			return redirect('admin/favicon/edit');
       
	}
	

	public function logoEdit() {
		
		$user = Auth::user();
		
		return view('admin.dashboard.index-logo')->with('title',$user->name.'  Edit Logo');
		
    }

	public function logoUpload(Request $request) {
		
			$validArr = array();
			if($request->file('image')) {
				$validArr['image'] = 'required|mimes:jpeg,jpg,png,gif|required|max:10000';
			}	
		
			$this->validate($request, $validArr);
			
			$requestData = $request->all();
			$imagetable = imagetable::where('table_name', 'logo')->first();
			
			if(count($imagetable) == 0) {
				
				$file = $request->file('image');
			
                $destination_path = public_path('uploads/imagetable/');
                $profileImage = date("Ymd").".".$file->getClientOriginalExtension();

                Image::make($file)->save($destination_path . DIRECTORY_SEPARATOR. $profileImage);

				$image = new imagetable;				
                $image->img_path = 'uploads/imagetable/'.$profileImage;
				$image->table_name = 'logo';
                $image->save();
				
				
			} else {
				
				if ($request->hasFile('image')) {
					
					$image_path = public_path($imagetable->img_path);
					
					if(File::exists($image_path)) {
						File::delete($image_path);
					}
				
					$file = $request->file('image');
					$fileNameExt = $request->file('image')->getClientOriginalName();
					$fileNameForm = str_replace(' ', '_', $fileNameExt);
					$fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
					$fileExt = $request->file('image')->getClientOriginalExtension();
					$fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
					
					
					$pathToStore = public_path('uploads/imagetable/');
					Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

				
					imagetable::where('table_name', 'logo')
							->update(['img_path' => 'uploads/imagetable/'.$fileNameToStore]);
					
				}

			}
			
			
			
			$footer_imagetable = imagetable::where('table_name', 'footer_logo')->first();
			
			if(count($imagetable) == 0) {
				
				$file = $request->file('footer_logo');
			
                $destination_path = public_path('uploads/imagetable/');
                $profileImage = date("Ymd").".".$file->getClientOriginalExtension();

                Image::make($file)->save($destination_path . DIRECTORY_SEPARATOR. $profileImage);

				$footer_logo = new imagetable;				
                $footer_logo->img_path = 'uploads/imagetable/'.$profileImage;
				$footer_logo->table_name = 'footer_logo';
                $image->save();
				
				
			} else {
				
				if ($request->hasFile('footer_logo')) {
					
					$image_path = public_path($imagetable->img_path);
					
					if(File::exists($image_path)) {
						File::delete($image_path);
					}
				
					$file = $request->file('footer_logo');
					$fileNameExt = $request->file('footer_logo')->getClientOriginalName();
					$fileNameForm = str_replace(' ', '_', $fileNameExt);
					$fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
					$fileExt = $request->file('footer_logo')->getClientOriginalExtension();
					$fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
					
					
					$pathToStore = public_path('uploads/imagetable/');
					Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

				
					imagetable::where('table_name', 'footer_logo')
							->update(['img_path' => 'uploads/imagetable/'.$fileNameToStore]);
					
				}

			}

			session()->flash('message', 'Successfully updated the logo');
			return redirect('admin/logo/edit');
	}


	public function contactSubmissions() {
	 	$contact_inquiries = DB::table('inquiry')->get();
	 	return view('admin.inquires.contact_inquiries', compact('contact_inquiries'));

	}
	
	public function contactSubmissionsDelete($id) {

		  $del = DB::table('inquiry')->where('id',$id)->delete();
		  
		  if($del) { 
			  return redirect('admin/contact/inquiries')->with('flash_message', 'Contact deleted!');
		  }
			
	}	

    public function inquiryshow($id)
    {
            $inquiry = inquiry::findOrFail($id);
            return view('admin.inquires.inquirydetail', compact('inquiry'));
    }
    
	public function newsletterInquiries() {
		
	 	$newsletter_inquiries = DB::table('newsletter')->get();

	 	return view('admin.inquires.newsletter_inquiries', compact('newsletter_inquiries'));

	}
	
	public function newsletterInquiriesDelete($id) {
		  $del = DB::table('newsletter')->where('id',$id)->delete();
		  
		  if($del) { 
			  return redirect('admin/newsletter/inquiries')->with('flash_message', 'Contact deleted!');
		  }
			
	}	
    
    public function changePrdStatus(Request $request){
        
        
        
        $prdVid = ProductVideo::find($request->item_id);
        $prdVid->show_on_home = $request->status;
        $prdVid->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
    
	public function configSetting(){
		return view('admin.dashboard.index-config');
	}

}
