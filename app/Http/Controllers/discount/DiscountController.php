<?php

namespace App\Http\Controllers\discount;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Discount;
use Illuminate\Http\Request;
use Image;
use File;
use App\Category;

class DiscountController extends Controller
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $discount = Discount::where('cat_id', 'LIKE', "%$keyword%")
                ->orWhere('date_range', 'LIKE', "%$keyword%")
                ->orWhere('discount_price', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $discount = Discount::get();
            }

            return view('discount.discount.index', compact('discount'));
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $items = Category::all();
            return view('discount.discount.create',compact('items'));
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'cat_id' => 'required'
		]);

            $discount = new Discount($request->all());
            
            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $discount_path = 'uploads/discounts/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($discount_path) . DIRECTORY_SEPARATOR. $profileImage);

                $discount->image = $discount_path.$profileImage;
            }
            
            $discount->save();
            return redirect()->back()->with('message', 'Discount added!');
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $discount = Discount::findOrFail($id);
            return view('discount.discount.show', compact('discount'));
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $discount = Discount::findOrFail($id);
            $items = Category::all();
            return view('discount.discount.edit', compact('discount','items'));
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
			'cat_id' => 'required'
		]);
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $discount = Discount::where('id', $id)->first();
            $image_path = public_path($discount->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/discounts/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/discounts/'.$fileNameToStore;               
        }


            $discount = Discount::findOrFail($id);
            $discount->update($requestData);
            return redirect()->back()->with('message', 'Discount updated!');
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
        $model = str_slug('discount','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Discount::destroy($id);
            return redirect()->back()->with('message', 'Discount deleted!');
        }
        return response(view('403'), 403);

    }
}
