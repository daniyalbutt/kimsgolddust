<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Redirection;
use Illuminate\Http\Request;
use Image;
use File;

class RedirectionController extends Controller
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
                $testimonial = Redirection::where('name', 'LIKE', "%$keyword%")
                ->orWhere('verified', 'LIKE', "%$keyword%")
                ->orWhere('comments', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $testimonial = Redirection::all();
            }

            return view('admin.redirection.index', compact('testimonial'));
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
            return view('admin.redirection.create');
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
    			'url' => 'required',
    			'redirect_url' => 'required',
    		]);
            $requestData = $request->all();
            Redirection::create($requestData);
            session()->flash('message', 'Redirection Added Successfully');
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
            $testimonial = Redirection::findOrFail($id);
            return view('admin.redirection.show', compact('testimonial'));
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
            $testimonial = Redirection::findOrFail($id);
            return view('admin.redirection.edit', compact('testimonial'));
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
    			'url' => 'required',
    			'redirect_url' => 'required',
    		]);
            $requestData = $request->all();
            

            $testimonial = Redirection::where('id', $id)->first();

            $testimonial->update($requestData);
            session()->flash('message', 'Redirection updated Successfully');
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
            Redirection::destroy($id);

            return redirect('admin/redirection')->with('flash_message', 'Redirection deleted!');
        }
        return response(view('403'), 403);

    }
}