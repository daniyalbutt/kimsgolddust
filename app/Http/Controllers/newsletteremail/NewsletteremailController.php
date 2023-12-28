<?php

namespace App\Http\Controllers\newsletteremail;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Newsletteremail;
use Illuminate\Http\Request;
use Image;
use File;

class NewsletteremailController extends Controller
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $newsletteremail = Newsletteremail::where('heading', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $newsletteremail = Newsletteremail::paginate($perPage);
            }

            return view('newsletteremail.newsletteremail.index', compact('newsletteremail'));
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('newsletteremail.newsletteremail.create');
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            

            $newsletteremail = new Newsletteremail($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $newsletteremail_path = 'uploads/newsletteremails/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($newsletteremail_path) . DIRECTORY_SEPARATOR. $profileImage);

                $newsletteremail->image = $newsletteremail_path.$profileImage;
            }
            
            $newsletteremail->save();
            return redirect()->back()->with('message', 'Newsletteremail added!');
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $newsletteremail = Newsletteremail::findOrFail($id);
            return view('newsletteremail.newsletteremail.show', compact('newsletteremail'));
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $newsletteremail = Newsletteremail::findOrFail($id);
            return view('newsletteremail.newsletteremail.edit', compact('newsletteremail'));
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $newsletteremail = Newsletteremail::where('id', $id)->first();
            $image_path = public_path($newsletteremail->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/newsletteremails/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/newsletteremails/'.$fileNameToStore;               
        }


            $newsletteremail = Newsletteremail::findOrFail($id);
            $newsletteremail->update($requestData);
            return redirect()->back()->with('message', 'Newsletteremail updated!');
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
        $model = str_slug('newsletteremail','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Newsletteremail::destroy($id);
            return redirect()->back()->with('message', 'Newsletteremail deleted!');
        }
        return response(view('403'), 403);

    }
}
