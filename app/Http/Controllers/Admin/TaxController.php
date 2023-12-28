<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tax;
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $testimonial = Tax::where('name', 'LIKE', "%$keyword%")
                ->orWhere('verified', 'LIKE', "%$keyword%")
                ->orWhere('comments', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $testimonial = Tax::all();
            }

            return view('admin.tax.index', compact('testimonial'));
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            return view('admin.tax.create');
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
                'country_name' => 'required',
    			'state_code' => 'required',
    			'state_name' => 'required',
    			'percentage' => 'required'
    		]);

            $requestData = $request->all();
            Tax::create($requestData);
            session()->flash('message', 'Tax Added Successfully');
            return redirect()->back();
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $testimonial = Tax::findOrFail($id);
            return view('admin.tax.show', compact('testimonial'));
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $testimonial = Tax::findOrFail($id);
            return view('admin.tax.edit', compact('testimonial'));
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
    			'state_code' => 'required',
    			'state_name' => 'required',
    			'percentage' => 'required'
    		]);
    		
            $requestData = $request->all();
            

            $testimonial = Tax::where('id', $id)->first();
            
            $testimonial->update($requestData);
            session()->flash('message', 'Tax updated Successfully');
            return redirect()->back();
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
        $model = str_slug('testimonial','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Tax::destroy($id);

            return redirect('admin/tax')->with('flash_message', 'Tax deleted!');
        }
        return response(view('403'), 403);

    }
}