<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\ProductVideo;
use Illuminate\Http\Request;
use Image;
use File;
use DB;

class ProductVideosController extends Controller
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $productvideos = ProductVideo::where('name', 'LIKE', "%$keyword%")
                ->orWhere('video', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $productvideos = ProductVideo::all();
            }

            return view('product-videos.product-videos.index', compact('productvideos'));
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $pro = DB::table('products')->select('id' , 'product_title')->get();
            return view('product-videos.product-videos.create', compact('pro'));
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {

            $productvideos = new ProductVideo();
            $productvideos->name = $request->name;

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $filenameWithExt= $request->file('video')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('video')->getClientOriginalExtension();
                $fileNameToStore = $filename. '_'.time().'.'.$extension;
                $path =  public_path('uploads/videos/');
                $file->move($path, $fileNameToStore);
                $productvideos->video = 'uploads/videos/'.$fileNameToStore;
            }


            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $productvideos_path = 'uploads/productvideoss/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($productvideos_path) . DIRECTORY_SEPARATOR. $profileImage);

                $productvideos->image = $productvideos_path.$profileImage;
            }
            
            $productvideos->save();
            return redirect()->back()->with('message', 'ProductVideo added!');
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $productvideo = ProductVideo::findOrFail($id);
            return view('product-videos.product-videos.show', compact('productvideo'));
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $productvideo = ProductVideo::findOrFail($id);
            $pro = DB::table('products')->select('id' , 'product_title')->get();
            return view('product-videos.product-videos.edit', compact('productvideo', 'pro'));
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            
            $requestData = $request->all();
            
            if ($request->hasFile('video')) {
                $productvideos = ProductVideo::where('id', $id)->first();
                $image_path = public_path($productvideos->video); 
                
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }

                $file = $request->file('video');
                $filenameWithExt= $request->file('video')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('video')->getClientOriginalExtension();
                $fileNameToStore = $filename. '_'.time().'.'.$extension;
                $path =  public_path('uploads/videos/');
                $file->move($path, $fileNameToStore);
                $requestData['video'] = 'uploads/videos/'.$fileNameToStore;
            }


        if ($request->hasFile('image')) {
            
            $productvideos = ProductVideo::where('id', $id)->first();
            $image_path = public_path($productvideos->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/productvideoss/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/productvideoss/'.$fileNameToStore;               
        }


            $productvideo = ProductVideo::findOrFail($id);
            $productvideo->update($requestData);
            return redirect()->back()->with('message', 'ProductVideo updated!');
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
        $model = str_slug('productvideos','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            ProductVideo::destroy($id);
            return redirect()->back()->with('message', 'ProductVideo deleted!');
        }
        return response(view('403'), 403);

    }
}
