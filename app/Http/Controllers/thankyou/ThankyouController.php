<?php

namespace App\Http\Controllers\thankyou;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Thankyou;
use Illuminate\Http\Request;
use Image;
use File;

class ThankyouController extends Controller
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $thankyou = Thankyou::where('heading', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $thankyou = Thankyou::paginate($perPage);
            }

            return view('thankyou.thankyou.index', compact('thankyou'));
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('thankyou.thankyou.create');
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            

            $thankyou = new Thankyou($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $thankyou_path = 'uploads/thankyous/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($thankyou_path) . DIRECTORY_SEPARATOR. $profileImage);

                $thankyou->image = $thankyou_path.$profileImage;
            }
            
            $thankyou->save();
            return redirect()->back()->with('message', 'Thankyou added!');
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $thankyou = Thankyou::findOrFail($id);
            return view('thankyou.thankyou.show', compact('thankyou'));
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $thankyou = Thankyou::findOrFail($id);
            return view('thankyou.thankyou.edit', compact('thankyou'));
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $thankyou = Thankyou::where('id', $id)->first();
            $image_path = public_path($thankyou->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/thankyous/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/thankyous/'.$fileNameToStore;               
        }


            $thankyou = Thankyou::findOrFail($id);
            $thankyou->update($requestData);
            return redirect()->back()->with('message', 'Thankyou updated!');
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
        $model = str_slug('thankyou','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Thankyou::destroy($id);
            return redirect()->back()->with('message', 'Thankyou deleted!');
        }
        return response(view('403'), 403);

    }
}
