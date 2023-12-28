<?php

namespace App\Http\Controllers\shippings;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Shipping;
use Illuminate\Http\Request;
use Image;
use File;
use DB;
use App\Models\Tax;
class ShippingsController extends Controller
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
        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $shippings = Shipping::where('zone_name', 'LIKE', "%$keyword%")
                ->orWhere('region', 'LIKE', "%$keyword%")
                ->orWhere('shipping_method', 'LIKE', "%$keyword%")
                ->paginate($perPage);
            } else {
                $shippings = Shipping::paginate($perPage);
            }

            return view('shippings.shippings.index', compact('shippings'));
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
        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $countries = DB::table('countries')->get();
            return view('shippings.shippings.create',compact('countries'));
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

        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','add-'.$model)->first()!= null) {
            $this->validate($request, [
    			'zone_name' => 'required',
    			'region' => 'required'
    		]);

            $shippings = new Shipping($request->all());
            $shippings->region= json_encode($request->region);
            if ($request->hasFile('image')) {

                $file = $request->file('image');

                //make sure yo have image folder inside your public
                $shippings_path = 'uploads/shippingss/';
                $fileName = $file->getClientOriginalName();
                $profileImage = date("Ymd").$fileName.".".$file->getClientOriginalExtension();

                Image::make($file)->save(public_path($shippings_path) . DIRECTORY_SEPARATOR. $profileImage);

                $shippings->image = $shippings_path.$profileImage;
            }

            $shippings->save();

            $attTax = $request->taxes;

            for($i = 0; $i < count($attTax); $i++)
            {
                $zone_tax = new Tax;
                if($attTax[$i]['min'] != ''){
                    $zone_tax->condition = $attTax[$i]['condition'];
                    $zone_tax->min = $attTax[$i]['min'];
                    $zone_tax->max = $attTax[$i]['max'];
                    $zone_tax->row_cost = $attTax[$i]['row_cost'];
                    $zone_tax->zone_id = $shippings->id;
                    $zone_tax->save();
                }
            }
            return redirect()->back()->with('message', 'Shipping added!');
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
        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','view-'.$model)->first()!= null) {
            $shipping = Shipping::findOrFail($id);

            return view('shippings.shippings.show', compact('shipping'));
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
        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $shipping = Shipping::findOrFail($id);
            $countries = DB::table('countries')->get();
            $tax = Tax::all();
            return view('shippings.shippings.edit', compact('shipping','countries'));
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
        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','edit-'.$model)->first()!= null) {
            $this->validate($request, [
    			'zone_name' => 'required',
    			'region' => 'required'
    		]);
            $requestData = $request->all();


        if ($request->hasFile('image')) {

            $shippings = Shipping::where('id', $id)->first();
            $image_path = public_path($shippings->image);

            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            $file = $request->file('image');
            $fileNameExt = $request->file('image')->getClientOriginalName();
            $fileNameForm = str_replace(' ', '_', $fileNameExt);
            $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
            $fileExt = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
            $pathToStore = public_path('uploads/shippingss/');
            Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR. $fileNameToStore);

             $requestData['image'] = 'uploads/shippingss/'.$fileNameToStore;
        }


            $shipping = Shipping::findOrFail($id);
            $shipping->update($requestData);
            
            $attval = $request->taxes;
            for($i = 0; $i < count($attval); $i++)
            {
                $tax = new Tax;
                if($attval[$i]['min'] != ''){
                    $tax->condition = $attval[$i]['condition'];
                    $tax->min = $attval[$i]['min'];
                    $tax->max = $attval[$i]['max'];
                    $tax->row_cost = $attval[$i]['row_cost'];
                    $tax->zone_id = $id;
                    $tax->save();
                }
            }
            
            $tax_zone_id = $request->tax_zone_id;
            $min = $request->min;
            $max = $request->max;
            $row_cost = $request->row_cost;
            
            for($i = 0; $i < count($tax_zone_id); $i++){
                $tax = Tax::find($tax_zone_id[$i]);
                $tax->min = $min[$i];
                $tax->max = $max[$i];
                $tax->row_cost = $row_cost[$i];
                $tax->save();
            }
            
            return redirect()->back()->with('message', 'Shipping updated!');
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
        $model = str_slug('shippings','-');
        if(auth()->user()->permissions()->where('name','=','delete-'.$model)->first()!= null) {
            Shipping::destroy($id);
            return redirect()->back()->with('message', 'Shipping deleted!');
        }
        return response(view('403'), 403);

    }
    public function deleteProVariant(request $request){
        $id = $request->id;
        $tax_variant = DB::table('taxes')
                                ->where('id', $id)
                                ->delete();
        if($tax_variant){
            return response()->json(['message'=> "Update", 'status' => true]);
        }else{
            return response()->json(['message'=>'Error Occurred', 'status' => false]);
        }

    }
}
