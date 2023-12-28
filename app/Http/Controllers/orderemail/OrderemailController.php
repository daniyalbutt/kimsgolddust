<?php

namespace App\Http\Controllers\orderemail;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Orderemail;
use Illuminate\Http\Request;
use Image;
use File;

class OrderemailController extends Controller
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $orderemail = Orderemail::where('heading', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $orderemail = Orderemail::paginate($perPage);
            }

            return view('orderemail.orderemail.index', compact('orderemail'));
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('orderemail.orderemail.create');
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'heading' => 'required',
			'content' => 'required'
		]);

            $orderemail = new Orderemail($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $orderemail_path = 'uploads/orderemails/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($orderemail_path) . DIRECTORY_SEPARATOR. $profileImage);

                $orderemail->image = $orderemail_path.$profileImage;
            }
            
            $orderemail->save();
            return redirect()->back()->with('message', 'Orderemail added!');
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $orderemail = Orderemail::findOrFail($id);
            return view('orderemail.orderemail.show', compact('orderemail'));
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $orderemail = Orderemail::findOrFail($id);
            return view('orderemail.orderemail.edit', compact('orderemail'));
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
			'heading' => 'required',
			'content' => 'required'
		]);
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $orderemail = Orderemail::where('id', $id)->first();
            $image_path = public_path($orderemail->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/orderemails/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/orderemails/'.$fileNameToStore;               
        }


            $orderemail = Orderemail::findOrFail($id);
            $orderemail->update($requestData);
            return redirect()->back()->with('message', 'Orderemail updated!');
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
        $model = str_slug('orderemail','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Orderemail::destroy($id);
            return redirect()->back()->with('message', 'Orderemail deleted!');
        }
        return response(view('403'), 403);

    }
}
