<?php

namespace App\Http\Controllers\tax;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Tax;
use Illuminate\Http\Request;
use Image;
use File;

class TaxController extends Controller
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $tax = Tax::where('zone_id', 'LIKE', "%$keyword%")
                ->orWhere('condition', 'LIKE', "%$keyword%")
                ->orWhere('min', 'LIKE', "%$keyword%")
                ->orWhere('max', 'LIKE', "%$keyword%")
                ->orWhere('row_cost', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $tax = Tax::paginate($perPage);
            }

            return view('tax.tax.index', compact('tax'));
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('tax.tax.create');
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
			'zone_id' => 'required',
			'condition' => 'required',
			'min' => 'required',
			'max' => 'required'
		]);

            $tax = new Tax($request->all());

            if ($request->hasFile('image')) {

                $file = $request->file('image');
                
                //make sure yo have image folder inside your public
                $tax_path = 'uploads/taxs/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($tax_path) . DIRECTORY_SEPARATOR. $profileImage);

                $tax->image = $tax_path.$profileImage;
            }
            
            $tax->save();
            return redirect()->back()->with('message', 'Tax added!');
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $tax = Tax::findOrFail($id);
            return view('tax.tax.show', compact('tax'));
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $tax = Tax::findOrFail($id);
            return view('tax.tax.edit', compact('tax'));
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
			'zone_id' => 'required',
			'condition' => 'required',
			'min' => 'required',
			'max' => 'required'
		]);
            $requestData = $request->all();
            

        if ($request->hasFile('image')) {
            
            $tax = Tax::where('id', $id)->first();
            $image_path = public_path($tax->image); 
            
            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/taxs/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/taxs/'.$fileNameToStore;               
        }


            $tax = Tax::findOrFail($id);
            $tax->update($requestData);
            return redirect()->back()->with('message', 'Tax updated!');
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
        $model = str_slug('tax','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Tax::destroy($id);
            return redirect()->back()->with('message', 'Tax deleted!');
        }
        return response(view('403'), 403);

    }
}
