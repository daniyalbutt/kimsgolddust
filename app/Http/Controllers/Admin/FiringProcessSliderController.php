<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\FiringProcessSlider;
use Illuminate\Http\Request;
use Image;
use File;

class FiringProcessSliderController extends Controller
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $firingprocessslider = FiringProcessSlider::where('image', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $firingprocessslider = FiringProcessSlider::all();
            }

            return view('firingprocessslider.firing-process-slider.index', compact('firingprocessslider'));
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('firingprocessslider.firing-process-slider.create');
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'image' => 'required'
		]);

            $firingprocessslider = new FiringProcessSlider($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $firingprocessslider_path = 'uploads/firingprocesssliders/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($firingprocessslider_path) . DIRECTORY_SEPARATOR. $profileImage);

                $firingprocessslider->image = $firingprocessslider_path.$profileImage;
            }
            
            $firingprocessslider->save();
            return redirect()->back()->with('message', 'FiringProcessSlider added!');
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $firingprocessslider = FiringProcessSlider::findOrFail($id);
            return view('firingprocessslider.firing-process-slider.show', compact('firingprocessslider'));
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $firingprocessslider = FiringProcessSlider::findOrFail($id);
            return view('firingprocessslider.firing-process-slider.edit', compact('firingprocessslider'));
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $firingprocessslider = FiringProcessSlider::where('id', $id)->first();
            $image_path = public_path($firingprocessslider->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/firingprocesssliders/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/firingprocesssliders/'.$fileNameToStore;               
        }


            $firingprocessslider = FiringProcessSlider::findOrFail($id);
            $firingprocessslider->update($requestData);
            return redirect()->back()->with('message', 'FiringProcessSlider updated!');
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
        $model = str_slug('firingprocessslider','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            FiringProcessSlider::destroy($id);
            return redirect()->back()->with('message', 'FiringProcessSlider deleted!');
        }
        return response(view('403'), 403);

    }
}
