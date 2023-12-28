<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\imagetable;
use App\Banner;
use Illuminate\Http\Request;
use Image;
use File;
use DB;

class BannerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('banner','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $banner = Banner::where('title', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $banner = Banner::orderBy('order_id', 'asc')->get();
            }

            return view('admin.banner.index', compact('banner'));
        }
        return response(view('403'), 403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('banner','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('admin.banner.create');
        }
        return response(view('403'), 403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('banner','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
                'image' => 'required|mimes:jpeg,jpg,png,gif|required|max:10000'
    		]);
            // $requestData = $request->all();
            $banner = new banner;
            
            $banner->title = $request->input('title');  
            $banner->image_alter_tag = $request->input('image_alter_tag');
            $banner->front_image_alter_tag = $request->input('front_image_alter_tag');
            $banner->right_banner_alter_tag = $request->input('right_banner_alter_tag');
            $banner->description = $request->input('description');
            $banner->additonal_class = $request->input('additonal_class');             
            
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $destination_path = 'uploads/banner/';
                $profileImage = date("YmdHis").".".$file->getClientOriginalExtension();
                Image::make($file)->save(public_path($destination_path) . DIRECTORY_SEPARATOR. $profileImage);
                $banner->image = $destination_path.$profileImage;
            }

            if ($request->hasFile('front_image')) {

                $front_image = $request->file('front_image');
                $destination_path = 'uploads/banner/';
                $profileImage = date("YmdHis")."-image.".$front_image->getClientOriginalExtension();
                Image::make($front_image)->save(public_path($destination_path) . DIRECTORY_SEPARATOR. $profileImage);
                $banner->front_image = $destination_path.$profileImage;
            }

            if ($request->hasFile('right_image')) {

                $right_image = $request->file('right_image');
                $destination_path = 'uploads/banner/';
                $profileImage = date("YmdHis")."-right-image.".$right_image->getClientOriginalExtension();
                Image::make($right_image)->save(public_path($destination_path) . DIRECTORY_SEPARATOR. $profileImage);
                $banner->right_image = $destination_path.$profileImage;
            }



            $banner->shop_link = $request->input('shop_link');
            $data = banner::whereRaw('order_id = (select max(`order_id`) from banners)')->first();
            $banner->order_id = $data->order_id + 1;
            $banner->save();
            session()->flash('message', 'Banner Added Successfully');
            return redirect()->back();
        }
        return response(view('403'), 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('banner','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $banner = Banner::findOrFail($id);
            return view('admin.banner.show', compact('banner'));
        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('banner','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $banner = Banner::findOrFail($id);


            return view('admin.banner.edit', compact('banner'));
        }
        return response(view('403'), 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {   
        $model = str_slug('banner','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
            
            ]);


        $requestData['title'] = $request->input('title');
        $requestData['image_alter_tag'] = $request->input('image_alter_tag');
        $requestData['front_image_alter_tag'] = $request->input('front_image_alter_tag');
        $requestData['right_banner_alter_tag'] = $request->input('right_banner_alter_tag');
        $requestData['description'] = $request->input('description');
        $requestData['shop_link'] = $request->input('shop_link');
        $requestData['additonal_class'] = $request->input('additonal_class');
        
        $banner = banner::where('id', $id)->first();

        if ($request->hasFile('image')) {	
			$image_path = public_path($banner->image);	
			if(File::exists($image_path)) {	
				File::delete($image_path);
			} 
            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/banner/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);
			$requestData['image'] = 'uploads/banner/'.$fileNameToStore;        
			
        }

        if ($request->hasFile('right_image')) {   
            $image_path = public_path($banner->right_image);  
            if(File::exists($image_path)) { 
                File::delete($image_path);
            } 
            $file = $request->file('right_image');
            $fileNameExt = $request->file('right_image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('right_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/banner/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);
            $requestData['right_image'] = 'uploads/banner/'.$fileNameToStore;        
            
        }

        if ($request->hasFile('front_image')) {   
            $image_path = public_path($banner->front_image);  
            if(File::exists($image_path)) { 
                File::delete($image_path);
            } 
            $file = $request->file('front_image');
            $fileNameExt = $request->file('front_image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('front_image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/banner/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);
            $requestData['front_image'] = 'uploads/banner/'.$fileNameToStore;        
            
        }

        $banner->update($requestData);
       
        session()->flash('message', 'Successfully updated the Banner');
        return redirect()->back();
        }
        return response(view('403'), 403);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
       // $model = str_slug('banner','-');
       // if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Banner::destroy($id);

            return redirect('admin/banner')->with('flash_message', 'Banner deleted!');
       // }
       // return response(view('403'), 403);

    }
    
    public function changeStatus(Request $request){
        
        $banner = banner::find($request->banner_id);
        $banner->status = $request->status;
        $banner->save();
  
        return response()->json(['success'=>'Status change successfully.']);
    }
    
    public function bannerOrder(Request $request){
        $new_data = $request->new_data;
        $old_data = $request->old_data;
        $new_schedule = Banner::where('order_id', $new_data)->first();
        $old_schedule = Banner::where('order_id', $old_data)->first();
        $new_schedule->order_id = $old_data;
        $old_schedule->order_id = $new_data;
        $new_schedule->save();
        $old_schedule->save();
    }
}
