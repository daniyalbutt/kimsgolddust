<?php

namespace App\Http\Controllers\newaccount;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Newaccount;
use Illuminate\Http\Request;
use Image;
use File;

class NewaccountController extends Controller
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $newaccount = Newaccount::where('heading', 'LIKE', "%$keyword%")
                ->orWhere('content', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $newaccount = Newaccount::paginate($perPage);
            }

            return view('newaccount.newaccount.index', compact('newaccount'));
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('newaccount.newaccount.create');
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'heading' => 'required',
			'content' => 'required'
		]);

            $newaccount = new Newaccount($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $newaccount_path = 'uploads/newaccounts/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($newaccount_path) . DIRECTORY_SEPARATOR. $profileImage);

                $newaccount->image = $newaccount_path.$profileImage;
            }
            
            $newaccount->save();
            return redirect()->back()->with('message', 'Newaccount added!');
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $newaccount = Newaccount::findOrFail($id);
            return view('newaccount.newaccount.show', compact('newaccount'));
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $newaccount = Newaccount::findOrFail($id);
            return view('newaccount.newaccount.edit', compact('newaccount'));
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
			'heading' => 'required',
			'content' => 'required'
		]);
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $newaccount = Newaccount::where('id', $id)->first();
            $image_path = public_path($newaccount->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/newaccounts/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/newaccounts/'.$fileNameToStore;               
        }


            $newaccount = Newaccount::findOrFail($id);
            $newaccount->update($requestData);
            return redirect()->back()->with('message', 'Newaccount updated!');
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
        $model = str_slug('newaccount','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Newaccount::destroy($id);
            return redirect()->back()->with('message', 'Newaccount deleted!');
        }
        return response(view('403'), 403);

    }
}
