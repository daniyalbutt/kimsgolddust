<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Image;
use File;

class CouponController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $coupon = Coupon::where('type', 'LIKE', "%$keyword%")
                ->orWhere('price', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $coupon = Coupon::all();
            }

            return view('coupon.coupon.index', compact('coupon'));
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
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('coupon.coupon.create');
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
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
                'type' => 'required',
                'price' => 'required',
                'expire' => 'required'
            ]);

            $coupon = new Coupon($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $coupon_path = 'uploads/coupons/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($coupon_path) . DIRECTORY_SEPARATOR. $profileImage);

                $coupon->image = $coupon_path.$profileImage;
            }
            
            if($request->code != null){
                $coupon->is_coupons = 0;
            }
            
            if($request->checkout_max_price != null){
                $coupon->is_coupons = 1;
            }
            
            
            
            $coupon->save();
            return redirect()->back()->with('message', 'Coupon added!');
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
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $coupon = Coupon::findOrFail($id);
            return view('coupon.coupon.show', compact('coupon'));
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
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $coupon = Coupon::findOrFail($id);
            return view('coupon.coupon.edit', compact('coupon'));
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
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
                'type' => 'required',
                'price' => 'required',
                'expire' => 'required'
            ]);
            $requestData = $request->all();
            
            if($requestData['code'] != null){
                $requestData['is_coupons'] = 0;
            }
            
            if($requestData['checkout_max_price'] != null){
                $requestData['is_coupons'] = 1;
            }
            

        if ($request->hasFile('image')) {
            
            $coupon = Coupon::where('id', $id)->first();
            $image_path = public_path($coupon->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/coupons/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/coupons/'.$fileNameToStore;               
        }


            $coupon = Coupon::findOrFail($id);
            $coupon->update($requestData);
            return redirect()->back()->with('message', 'Coupon updated!');
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
        $model = str_slug('coupon','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Coupon::destroy($id);
            return redirect()->back()->with('message', 'Coupon deleted!');
        }
        return response(view('403'), 403);

    }
    public function changeStatus(Request $request){
        
        $coupon = Coupon::find($request->coupon_id);
        $coupon->status = $request->status;
        $coupon->save();
  
        return response()->json(['success'=>'Status change successfully.']);
        
    }
}
