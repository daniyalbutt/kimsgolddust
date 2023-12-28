<?php

namespace App\Http\Controllers\resetpassword;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Resetpassword;
use Illuminate\Http\Request;
use Image;
use File;

class ResetpasswordController extends Controller
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $resetpassword = Resetpassword::where('Heading', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $resetpassword = Resetpassword::paginate($perPage);
            }

            return view('resetpassword.resetpassword.index', compact('resetpassword'));
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('resetpassword.resetpassword.create');
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'Heading' => 'required',
			'content' => 'required'
		]);

            $resetpassword = new Resetpassword($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $resetpassword_path = 'uploads/resetpasswords/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($resetpassword_path) . DIRECTORY_SEPARATOR. $profileImage);

                $resetpassword->image = $resetpassword_path.$profileImage;
            }
            
            $resetpassword->save();
            return redirect()->back()->with('message', 'Resetpassword added!');
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $resetpassword = Resetpassword::findOrFail($id);
            return view('resetpassword.resetpassword.show', compact('resetpassword'));
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $resetpassword = Resetpassword::findOrFail($id);
            return view('resetpassword.resetpassword.edit', compact('resetpassword'));
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
			'Heading' => 'required',
			'content' => 'required'
		]);
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $resetpassword = Resetpassword::where('id', $id)->first();
            $image_path = public_path($resetpassword->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/resetpasswords/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/resetpasswords/'.$fileNameToStore;               
        }


            $resetpassword = Resetpassword::findOrFail($id);
            $resetpassword->update($requestData);
            return redirect()->back()->with('message', 'Resetpassword updated!');
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
        $model = str_slug('resetpassword','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Resetpassword::destroy($id);
            return redirect()->back()->with('message', 'Resetpassword deleted!');
        }
        return response(view('403'), 403);

    }
}
