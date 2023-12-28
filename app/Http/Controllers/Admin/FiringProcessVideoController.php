<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\FiringProcessVideo;
use Illuminate\Http\Request;
use Image;
use File;

class FiringProcessVideoController extends Controller
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $firingprocessvideo = FiringProcessVideo::where('link', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $firingprocessvideo = FiringProcessVideo::all();
            }

            return view('firingprocessvideo.firing-process-video.index', compact('firingprocessvideo'));
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('firingprocessvideo.firing-process-video.create');
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'link' => 'required'
		]);

            $firingprocessvideo = new FiringProcessVideo($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $firingprocessvideo_path = 'uploads/firingprocessvideos/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($firingprocessvideo_path) . DIRECTORY_SEPARATOR. $profileImage);

                $firingprocessvideo->image = $firingprocessvideo_path.$profileImage;
            }
            
            $firingprocessvideo->save();
            return redirect()->back()->with('message', 'FiringProcessVideo added!');
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $firingprocessvideo = FiringProcessVideo::findOrFail($id);
            return view('firingprocessvideo.firing-process-video.show', compact('firingprocessvideo'));
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $firingprocessvideo = FiringProcessVideo::findOrFail($id);
            return view('firingprocessvideo.firing-process-video.edit', compact('firingprocessvideo'));
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
			'link' => 'required'
		]);
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $firingprocessvideo = FiringProcessVideo::where('id', $id)->first();
            $image_path = public_path($firingprocessvideo->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/firingprocessvideos/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/firingprocessvideos/'.$fileNameToStore;               
        }


            $firingprocessvideo = FiringProcessVideo::findOrFail($id);
            $firingprocessvideo->update($requestData);
            return redirect()->back()->with('message', 'FiringProcessVideo updated!');
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
        $model = str_slug('firingprocessvideo','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            FiringProcessVideo::destroy($id);
            return redirect()->back()->with('message', 'FiringProcessVideo deleted!');
        }
        return response(view('403'), 403);

    }
}
