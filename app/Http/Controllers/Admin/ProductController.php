<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Category;
use App\orders;
use App\orders_products;
use App\Product;
use App\imagetable;
use App\Attributes;
use App\AttributeValue;
use App\ProductAttribute;
use Illuminate\Http\Request;
use Image;
use File;
use DB;
use Session;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $logo = imagetable::select('img_path')
            ->where('table_name', '=', 'logo')
            ->first();

        $favicon = imagetable::select('img_path')
            ->where('table_name', '=', 'favicon')
            ->first();

        View()->share('logo', $logo);
        View()->share('favicon', $favicon);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {   
        
        $stock = $request->stock;
       
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'view-' . $model)->first() != null) {
            $keyword = $request->get('search');
            $perPage = 25;

            if (!empty($keyword)) {
                $product = Product::where('products.product_title', 'LIKE', "%$keyword%")
                    ->leftjoin('categories', 'products.category', '=', 'categories.id')
                    ->orWhere('products.description', 'LIKE', "%$keyword%")
                    ->paginate($perPage);
            } else {
                    if($request->catId != ""){
                        $cat = $request->catId ;
                        $productCAt = DB::table('product_category')->whereIn('category_id',$cat)->get();
                        // $product = Product::whereIn('id',$productCAt['product_id'])->get();
                        $prdArray = [];
                        // dump($relPrd);
                        foreach($productCAt as $key => $value){
                            array_push($prdArray,$value->product_id);
                        }
                        
                        
                        if($request->stock == 0){
                            $product = Product::whereIn('id',$prdArray)->where('stock',0)->get();                        
                        }else if($request->stock == 1){
                            $product = Product::whereIn('id',$prdArray)->where('stock','!=',0)->get();
                        }else if($request->stock == 2){
                            $product = Product::whereIn('id',$prdArray)->get();
                        }
                        else{
                            $product = Product::whereIn('id',$prdArray)->get();
                        }
                        // dd($product);
                    }else{
                        
                        $product = Product::all();
                    }
                $category =  Category::all();
            }
            return view('admin.product.index', compact('product','category','cat','stock'));
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
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'add-' . $model)->first() != null) {
            $att = Attributes::all();
            $attval = AttributeValue::all();
            $items = Category::all();
            $main_cat = Category::where('parent', 0)->get();
            $relatedPrd = Product::all();
            return view('admin.product.create', compact('items', 'att', 'attval', 'main_cat', 'relatedPrd'));
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
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'add-' . $model)->first() != null) {
            $this->validate($request, [
                'product_title' => 'required',
                'short_description' => 'required',
                'regular_price' => 'required',
                'image' => 'required',
                'item_id' => 'required',
            ]);
            $tags = null;
            $tag_output = json_decode($request->tags);
            foreach ($tag_output as $key => $value) {
                $tags .= $value->value;
                if ($key != count($tag_output) - 1) {
                    $tags .= ', ';
                }
            }

            $product = new product;
            if($request->slug != null){
                $product->slug = $request->slug;
            }else{
                $product->slug = str_slug($request->input('product_title'), '-');
            }
            $product->product_title = $request->input('product_title');
            // $product->new_product = $request->input('new_product');
            
            if($request->discount_get_qty == null){
                $product->discount_get_qty = 0;
            }else{
                $product->discount_get_qty = $request->input('discount_get_qty');
            }
            $product->price = $request->input('price');
            $product->regular_price = $request->input('regular_price');
            
            if($request->theseTogether != null){ 
                $product->theseTogether = json_encode($request->theseTogether);
            }
            
            
            if($request->discount_buy_qty == null){
                $product->discount_buy_qty = 0;
            }else{
                $product->discount_buy_qty = $request->input('discount_buy_qty');
            }
            
            if (($request->input('price') == 0) && ($request->input('regular_price') == 0)) {
            } elseif ($request->input('price') == 0) {
                $product->actual_price = $request->input('regular_price');
            } else {
                $product->actual_price = $request->input('price');
            }

            $product->sku = $request->input('sku');
            $product->seo_title = $request->input('seo_title');
            $product->seo_description = $request->input('seo_description');
            $product->seo_keyword = $request->input('seo_keyword');
            $product->additional_seo = $request->input('additional_seo');
            $product->short_description = $request->input('short_description');
            $product->stock = $request->input('stock');
            $cat = $request->input('item_id');
            $product->tags = $tags;
            $product->is_featured_home = $request->input('is_featured_home');
            $product->is_featured_menu = $request->input('is_featured_menu');
            $product->best_seller = $request->input('best_seller');
            $file = $request->file('image');
            $destination_path = 'uploads/product/';
            $profileImage = date("Ymdhis") . "." . $file->getClientOriginalExtension();
            Image::make($file)->save(public_path($destination_path) . DIRECTORY_SEPARATOR . $profileImage);
            $product->image = $destination_path . $profileImage;
            $product->save();
            $product->category_list()->attach($cat);
            if (!is_null(request('images'))) {
                $photos = request()->file('images');
                foreach ($photos as $photo) {
                    $destinationPath = 'uploads/product/';
                    $filename = date("Ymdhis") . uniqid() . "." . $photo->getClientOriginalExtension();
                    Image::make($photo)->save(public_path($destinationPath) . DIRECTORY_SEPARATOR . $filename);
                    DB::table('product_imagess')->insert([
                        [
                            'image' => $destination_path . $filename,
                            'product_id' => $product->id,
                            'is_variant' => 0
                        ]
                    ]);
                }
            }

            $attval = $request->attribute;
            $attr_price  = [];
            for ($i = 0; $i < count($attval); $i++) {
                $product_attributes = new ProductAttribute;
                if ($request->hasFile('attribute.' . $i . '.v-image')) {
                    $file = $request->file('attribute.' . $i . '.v-image');
                    $fileNameExt = $file->getClientOriginalName();
                    $fileNameForm = str_replace(' ', '_', $fileNameExt);
                    $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
                    $fileExt = $file->getClientOriginalExtension();
                    $fileNameToStore = $fileName . '_' . time() . '.' . $fileExt;
                    $pathToStore = public_path('uploads/product-attributes/');
                    Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR . $fileNameToStore);
                    $product_attributes->image = 'uploads/product-attributes/' . $fileNameToStore;
                }
                $product_attributes->attribute_id = $attval[$i]['attribute_id'];
                $product_attributes->value = $attval[$i]['value'];
                $product_attributes->price = $attval[$i]['v-price'];
                $product_attributes->regular_price = $attval[$i]['v-regular-price'];
                if ($attval[$i]['v-price'] == 0) {
                    array_push($attr_price, $attval[$i]['v-regular-price']);
                } else {
                    array_push($attr_price, $attval[$i]['v-price']);
                }
                $product_attributes->qty = $attval[$i]['qty'];
                $product_attributes->product_id = $product->id;
                if ($attval[$i]['value'] != null) {
                    $product_attributes->save();
                }
            }
            if (min($attr_price) != 0) {
                DB::table('products')->where('id', $product->id)->limit(1)->update(array('actual_price' => min($attr_price)));
            }
            Session::flash('message', 'Product added Successfully');
            Session::flash('alert-class', 'alert-success');
            return back();
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
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'view-' . $model)->first() != null) {
            $product = Product::findOrFail($id);
            return view('admin.product.show', compact('product'));
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
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'edit-' . $model)->first() != null) {
            $att = Attributes::all();
            $product = Product::findOrFail($id);
            $items = Category::all();
            $relatedPrd = Product::all();
            $product_images = DB::table('product_imagess')->where('product_id', $id)->get();
            $main_cat = Category::where('parent', 0)->get();
            return view('admin.product.edit', compact('product', 'items', 'product_images', 'att', 'main_cat','relatedPrd'));
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
       
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'edit-' . $model)->first() != null) {
            $this->validate($request, [
                'product_title' => 'required',
                'short_description' => 'required',
                'item_id' => 'required'
            ]);
            $tags = null;
            $tag_output = json_decode($request->tags);
            foreach ($tag_output as $key => $value) {
                $tags .= $value->value;
                if ($key != count($tag_output) - 1) {
                    $tags .= ', ';
                }
            }
            $requestData['slug'] = $request->slug;
            $requestData['product_title'] = $request->input('product_title');
            // $requestData['new_product'] = $request->input('new_product');
            $requestData['regular_price'] = $request->input('regular_price');
            $requestData['sku'] = $request->input('sku');
            $requestData['short_description'] = $request->input('short_description');
            if($request->theseTogether != null){ 
                $requestData['theseTogether'] = json_encode($request->theseTogether);
            }
            $requestData['price'] = $request->input('price');
            $requestData['stock'] = $request->input('stock');
            $cat = $request->input('item_id');
            $requestData['tags'] = $tags;
            $requestData['is_featured_home'] = $request->input('is_featured_home');
            $requestData['is_featured_menu'] = $request->input('is_featured_menu');
            $requestData['best_seller'] = $request->input('best_seller');
            $requestData['seo_title'] = $request->input('seo_title');
            $requestData['seo_description'] = $request->input('seo_description');
            $requestData['seo_keyword'] = $request->input('seo_keyword');
            $requestData['additional_seo'] = $request->input('additional_seo');
            
            if($request->discount_get_qty == null){
                $requestData['discount_get_qty'] = 0;
            }else{
                $requestData['discount_get_qty'] = $request->input('discount_get_qty');
            }
            
            if($request->discount_buy_qty == null){
                $requestData['discount_buy_qty'] = 0;
            }else{
                $requestData['discount_buy_qty'] = $request->input('discount_buy_qty');
            }
            
            if (($request->input('price') == 0) && ($request->input('regular_price') == 0)) {
            } elseif ($request->input('price') == 0) {
                $requestData['actual_price'] = $request->input('regular_price');
            } else {
                $requestData['actual_price'] = $request->input('price');
            }

            if ($request->hasFile('image')) {
                $product = product::where('id', $id)->first();
                $image_path = public_path($product->image);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
                $file = $request->file('image');
                $fileNameExt = $request->file('image')->getClientOriginalName();
                $fileNameForm = str_replace(' ', '_', $fileNameExt);
                $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
                $fileExt = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $fileName . '_' . time() . '.' . $fileExt;
                $pathToStore = public_path('uploads/product/');
                Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR . $fileNameToStore);
                $requestData['image'] = 'uploads/product/' . $fileNameToStore;
            }

            if (!is_null(request('images'))) {
                $photos = request()->file('images');
                foreach ($photos as $photo) {
                    $destinationPath = 'uploads/product/';
                    $filename = date("Ymdhis") . uniqid() . "." . $photo->getClientOriginalExtension();
                    Image::make($photo)->save(public_path($destinationPath) . DIRECTORY_SEPARATOR . $filename);
                    $product = product::where('id', $id)->first();
                    DB::table('product_imagess')->insert([
                        [
                            'image' => $destinationPath . $filename,
                            'product_id' => $product->id,
                            'is_variant' => 0
                        ]
                    ]);
                }
            }

            product::where('id', $id)->update($requestData);
            $pro = Product::find($id);
            $pro->category_list()->sync($cat);
            $attval = $request->attribute;
            $product_attribute_id = $request->product_attribute;
            $oldatt = $request->attribute_id;
            $oldval = $request->value;
            $oldprice = $request->v_price;
            $oldregularprice = $request->v_regular_price;
            $oldqty = $request->qty;
            $oldimage = $request->oldimage;
            $attr_price = [];
            
            if($product_attribute_id != null){
                for ($j = 0; $j < count($product_attribute_id); $j++) {
                    
                    $product_attribute = ProductAttribute::find($product_attribute_id[$j]);
                    if ($request->hasFile('oldimage.' . $j)) {
                        $image_path = public_path($product_attribute->image);
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        $file = $request->file('oldimage.' . $j);
                        $fileNameExt = $file->getClientOriginalName();
                        $fileNameForm = str_replace(' ', '_', $fileNameExt);
                        $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
                        $fileExt = $file->getClientOriginalExtension();
                        $fileNameToStore = $fileName . '_' . time() . '.' . $fileExt;
                        $pathToStore = public_path('uploads/product-attributes/');
                        Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR . $fileNameToStore);
                        $product_attribute->image = 'uploads/product-attributes/' . $fileNameToStore;
                    }
                    $product_attribute->price = $oldprice[$j];
                    $product_attribute->regular_price = $oldregularprice[$j];
                    if ($oldprice[$j] == 0) {
                        array_push($attr_price, $oldregularprice[$j]);
                    } else {
                        array_push($attr_price, $oldprice[$j]);
                    }
                    $product_attribute->qty = $oldqty[$j];
                    $product_attribute->save();
                }
            }

            for ($i = 0; $i < count($attval); $i++) {
                if ($attval[$i]['value'] != null) {
                    $product_attributes = new ProductAttribute;
                    if ($request->hasFile('attribute.' . $i . '.v-image')) {
                        $image_path = public_path($product_attribute->image);
                        if (File::exists($image_path)) {
                            File::delete($image_path);
                        }
                        $file = $request->file('attribute.' . $i . '.v-image');
                        $fileNameExt = $file->getClientOriginalName();
                        $fileNameForm = str_replace(' ', '_', $fileNameExt);
                        $fileName = pathinfo($fileNameForm, PATHINFO_FILENAME);
                        $fileExt = $file->getClientOriginalExtension();
                        $fileNameToStore = $fileName . '_' . time() . '.' . $fileExt;
                        $pathToStore = public_path('uploads/product-attributes/');
                        Image::make($file)->save($pathToStore . DIRECTORY_SEPARATOR . $fileNameToStore);
                        $product_attributes->image = 'uploads/product-attributes/' . $fileNameToStore;
                    }
                    $product_attributes->attribute_id = $attval[$i]['attribute_id'];
                    $product_attributes->value = $attval[$i]['value'];
                    $product_attributes->price = $attval[$i]['v-price'];
                    $product_attributes->regular_price = $attval[$i]['v-regular-price'];

                    if ($attval[$i]['v-price'] == 0) {
                        array_push($attr_price, $attval[$i]['v-regular-price']);
                    } else {
                        array_push($attr_price, $attval[$i]['v-price']);
                    }

                    $product_attributes->qty = $attval[$i]['qty'];
                    $product_attributes->product_id = $id;
                    $product_attributes->save();
                }
            }
            
            if(count($attr_price) != 0){
                if (min($attr_price) != 0) {
                    DB::table('products')->where('id', $id)->limit(1)->update(array('actual_price' => min($attr_price)));
                }
            }
            /*        
        if(! is_null(request('images'))) {
                
                
                DB::table('product_imagess')->where('product_id', '=', $id)->delete();
                
                $photos=request()->file('images');
                
                
                
                foreach ($photos as $photo) {
                    $destinationPath = 'uploads/products/';
                  
                    $fileName = uniqid() . "_" . $file->getClientOriginalName();
                    $file->move(storage_path($destinationPath), $fileName);
                    
                  
                    DB::table('product_imagess')->insert([
                        
                        ['image' => $destinationPath.$filename, 'product_id' => $product->id]
                       
                    ]);
                    
                }
            
        }        
        */
            Session::flash('message', 'Product updated Successfully');
            Session::flash('alert-class', 'alert-success');
            return back();
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
        $model = str_slug('product', '-');
        if (auth()->user()->permissions()->where('name', '=', 'delete-' . $model)->first() != null) {
            $data = ProductAttribute::where('product_id', $id)->get();
            foreach ($data as $key => $value) {
                if ($value->image != null) {
                    $image_path = public_path($value->image);
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $value->delete();
            }
            $prod_image = DB::table('product_imagess')->where('product_id', $id)->get();
            foreach ($prod_image as $key => $value) {
                if ($value->image != null) {
                    $image_path = public_path($value->image);
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                DB::table('product_imagess')->where('id', $value->id)->delete();
            }

            $product = Product::find($id);
            if ($product->image != null) {
                $image_path = public_path($product->image);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
            $product->delete();
            return redirect('admin/product')->with('message', 'Product deleted!');
        }
        return response(view('403'), 403);
    }
    
    public function changeStatus(Request $request){
        $Prd = Product::find($request->user_id);
        $Prd->sales = $request->status;
        $Prd->save();
  
        return response()->json(['success'=>'Status For Sales change successfully.']);
    }
    
    public function orderList()
    {

        $orders = orders::select('orders.*')
            ->get();

        return view('admin.ecommerce.order-list', compact('orders'));
    }

    public function orderListDetail($id)
    {

        $order_id = $id;
        $order = orders::where('id', $order_id)->first();
        $order_products = orders_products::where('orders_id', $order_id)->get();



        return view('admin.ecommerce.order-page')->with('title', 'Invoice #' . $order_id)->with(compact('order', 'order_products'))->with('order_id', $order_id);

        // return view('admin.ecommerce.order-page');	
    }

    public function updatestatuscompleted($id)
    {

        $order_id = $id;
        $order = DB::table('orders')
            ->where('id', $id)
            ->update(['order_status' => 'Completed']);


        Session::flash('message', 'Order Status Updated Successfully');
        Session::flash('alert-class', 'alert-success');
        return back();
    }
    public function updatestatusPending($id)
    {

        $order_id = $id;
        $order = DB::table('orders')
            ->where('id', $id)
            ->update(['order_status' => 'Pending']);


        Session::flash('message', 'Order Status Updated Successfully');
        Session::flash('alert-class', 'alert-success');
        return back();
    }
    public function changeNewDate(Request $request){
        $Prd = Product::find($request->user_id);
        $Prd->new_product = $request->status;
        $Prd->new_product_date = $request->expiryDate;
        $Prd->save();
    }
    
    public function changeBackOrder(Request $request){
        
        $Prd = Product::find($request->prd_id);
        $Prd->back_order = $request->status;
        $Prd->save();
  
        return response()->json(['success'=>'Status For Back Order change successfully.']); 
        
        
    }
    
    public function productTags(){
        $data = DB::table('product_tags')->pluck('name')->toArray();
        return response()->json(['data'=> $data]);
    }
}
