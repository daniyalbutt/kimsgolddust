@extends('layouts.main')

@php
    $title = $cat->seo_title != null ? $cat->seo_title : $cat->name;
    $description = $cat->seo_description;
    $keywords = $cat->seo_keyword;
    $req_cat_array = [];
    $req_cat_array_seo = [];
    $request_cat = app('request')->input('category');
    if ($request_cat != null) {
        foreach ($request_cat as $key => $value) {
            $single_cat = DB::table('categories')
                ->where('id', $value)
                ->first();
            array_push($req_cat_array, $single_cat->name);
            array_push($req_cat_array_seo, $single_cat->seo_title);
        }
        $title = $req_cat_array_seo[0] != null ? $req_cat_array_seo[0] : $req_cat_array[0];
    }
@endphp

@section('title', $title)
@section('description', $description)
@section('keywords', $keywords)

@section('additional_seo')
    {!! $cat->additional_seo !!}
@endsection

@section('content')

    @if (Session::has('message'))
        <script>
            $(document).ready(function() {

                toastr.{{ Session::get('message') }}('{{ Session::get('message') }}');

            });
        </script>
    @endif

    <?php
    $categories = DB::table('categories')->get();
    use App\wishlists;
    ?>
    
    @if($cat->id != 2)

    <section class="inner_banner">
        <div class="container">
            <div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">
                <h1>
                    @if (count($req_cat_array) == 0)
                        {{ $cat->name }}
                    @else
                        {{ implode(', ', $req_cat_array) }}
                    @endif
                </h1>
                <p>
                    Home /
                    @if (count($req_cat_array) == 0)
                        {{ $cat->name }}
                    @else
                        {{ implode(', ', $req_cat_array) }}
                    @endif
                </p>
            </div>
        </div>
    </section>
    
    @endif
    
    <section class="shop_sec1">
        <div class="container">
            <div class="shop_wrap">
                <div class="shop_left">
                    <form action="{{ route('categoryDetail', ['id' => $cat->id, 'slug' => str_slug($cat->name, '-')]) }}" class="subCatForm">
                        <div class="filter_head">
                            <p class="black_heading30">Filters</p>
                            <a href="javascript:void(0);" onclick="clearAllTag()">Clear All</a>
                        </div>
                        <div class="cat_field mb-4">
                            <input type="text" id="tags" name="" placeholder="Search Filters">
                            <button type="button" class="tag-button">
                                <i class="fa-solid fa-plus-circle"></i>
                            </button>
                        </div>
                        <div class="filter_tags">
                            @php
                                $tags_line = app('request')->input('tags');
                            @endphp
                            @foreach ($tags_line as $key => $value)
                                <span class="filter_tag"><input type="hidden" name="tags[]"
                                        value="{{ $value }}">{{ $value }}<i class="fa-solid fa-xmark"
                                        onclick="removeTag(this)"></i></span>
                            @endforeach
                        </div>
                        <div class="filter_head">
                            <p class="black_heading30">Categories</p>
                        </div>
                        <div class="cat_field mb-4">
                            <input type="text" name="q" placeholder="Search part # or keywords"
                                value="{{ app('request')->input('q') }}">
                            <button type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                            @php
                                $selected_cat = app('request')->input('category');
                                $catName = Request::segment(1);
                            @endphp
                        <div class="categories categories-filter-listing">
                            
                            <!--//First-->
                            <ul class="hmenu hmenu-visible" data-menu-id="1">
                                @foreach($all_cat_sidebar as $key => $value)
                                <li><a href="javascript:;" data-menu-id="{{$value->id}}"><div>{{ $value->name }} </div><i class="fa-solid fa-angle-right"></i></a></li>
                                
                                @endforeach
                            </ul>
                            
                            <!--//Second-->
                            @foreach($all_cat_sidebar as $key => $values)
                                @php
                                    $sub_cat = \DB::table('categories')->where('parent', $values->id)->get();
                                @endphp
                                @if (count($sub_cat) != 0)
                                     <ul class="hmenu hmenu-translateX-right " data-menu-id="{{$values->id}}" data-parent-menu-id="1">
                                        <li class="back-option"><a href="javascript:;" data-menu-id="1"><div><i class="fa-solid fa-angle-left"></i> {{ $values->name }} </div></a></li>
                                        @foreach ($sub_cat as $innerkey => $innervalue)
                                            @php
                                                $test_array_1 = [];
                                                $childcat = \DB::table('categories')->where('parent', $innervalue->id)->get();
                                                foreach($childcat as $first ){
                                                    array_push($test_array_1, $first->id);
                                                    $test_array_2 = [];
                                                    $childSubcat = \DB::table('categories')->where('parent', $first->id)->get();
                                                    foreach($childSubcat as $testkey => $testValue){
                                                        array_push($test_array_2, $testValue->id);
                                                    }
                                                    array_push($test_array_2, $first->id);
                                                    $count_three = \DB::table('product_category')->whereIn('category_id', $test_array_2)->count();
                                                }
                                                array_push($test_array_1, $innervalue->id);
                                                $arr_1_final = array_diff($test_array_1, $test_array_2);
                                                $count_two = \DB::table('product_category')->whereIn('category_id', $arr_1_final)->count();
                                            @endphp
                                        
                                                <!--class="category_item first-level "-->
                                                <li><a href="{{(count($sub_cat) > 0 )?'javascript:;':'#'}}" data-menu-id="{{ $innervalue->id }}" data-menu-slug="{{ $innervalue->slug }}" class="{{(count($sub_cat) > 0 )?'':'searchCat'}}"><div>{{ $innervalue->name }}<span class="category_qty">({{$count_two+$count_three}})</span></div><i class="{{(count($childcat) > 0 )?'fa-solid fa-angle-right':''}}"></i></a></li>
                                        
                                        @endforeach
                                    </ul>
                                     
                                @endif
                            @endforeach
                            <!--//Third-->
                            @foreach ($sub_cat as $innerkey => $innervalues)
                                @php
                                    
                                    $childcat = \DB::table('categories')->where('parent', $innervalues->id)->orderBy('order_category', 'ASC')->get();
                                    $main_count = 10;
                                @endphp
                                <ul class="hmenu hmenu-translateX-right" data-menu-id="{{$innervalues->id}}" data-parent-menu-id="1">
                                        <li class="back-option"><a href="javascript:;" data-menu-id="1"><div><i class="fa-solid fa-angle-left"></i> {{ $innervalues->name }} </div></a></li>
                                        
                                        @foreach ($childcat as $key => $innerval)
                                            @php
                                                $test_array = [];
                                                $childSubcat = \DB::table('categories')->where('parent', $innerval->id)->get();
                                                foreach($childSubcat as $testkey => $testValue){
                                                    array_push($test_array, $testValue->id);
                                                }
                                               
                                                
                                                array_push($test_array, $innerval->id);
                                                if(count($test_array) > 1){
                                                    $count = \DB::table('product_category')->whereIn('category_id', $test_array)->count();
                                                }else{
                                                    $count = DB::table('product_category')->join('products','product_category.product_id','=','products.id')->where('product_category.category_id', $innerval->id)->count();
                                                }
                                               
                                            @endphp
                                        
                                        <li><a href="{{(count($childSubcat) > 0 )?'javascript:;':'#'}}" data-menu-slug="{{ $innerval->slug }}" data-menu-id="{{ $innerval->id }}" class="{{(count($childSubcat) > 0 )?'':'searchCat'}}"><div>{{ $innerval->name }} <span class="category_qty">({{ $count }})</span></div><i class="{{(count($childSubcat) > 0 )?'fa-solid fa-angle-right':''}}"></i></a></li>
                                       
                                        @endforeach
                                </ul>
                            @endforeach
                            <!--//Fourth-->
                            @foreach ($sub_cat as $innerkey => $innervalues)
                            @php
                                $childcat = \DB::table('categories')->where('parent', $innervalues->id)->get();
                               
                            @endphp
                            @foreach($childcat as $innerkey => $innervaluess)
                                @php
                                
                                    $childSubcat = \DB::table('categories')->where('parent', $innervaluess->id)->get();
                                     $childInnerSubcat = \DB::table('categories')->where('parent', $innervaluess->id)->get();
                                @endphp
                                @if($childInnerSubcat != 0)
                                <ul class="nazr hmenu hmenu-translateX-right" data-menu-id="{{$innervaluess->id}}" data-parent-menu-id="1">
                                    <li class="back-option"><a href="javascript:;" data-menu-id="1"><div><i class="fa-solid fa-angle-left"></i> {{ $innervaluess->name }} </div></a></li>
                                    @php
                                    $last_count = 0;
                                    $last_count_link = '';
                                    @endphp
                                    @foreach($childSubcat as $key => $innervalChild)
                                        @php
                                            $count = \DB::table('product_category')->where('category_id', $innervalChild->id)->count();
                                            
                                        
                                                $test_array1 = [];
                                                $childInnerSubcat = \DB::table('categories')->where('parent', $innervalChild->id)->get();
                                                foreach($childInnerSubcat as $testkey => $testValue){
                                                    array_push($test_array1, $testValue->id);
                                                }
                                               
                                                
                                                array_push($test_array1, $innerval->id);
                                                $count1 = \DB::table('product_category')->whereIn('category_id', $test_array)->count();
                                        @endphp
                                        
                                        
                                    <li><a href="{{(count($childInnerSubcat) > 0 )?'javascript:;':'#'}}" data-menu-slug="{{$innervalChild->slug}}" data-menu-id="{{ $innervalChild->id }}" class="{{(count($childInnerSubcat) > 0 )?'':'searchCat'}}"><div>{{ $innervalChild->name }}<span class="category_qty">({{ $count }})</span></div><i class="{{(count($childInnerSubcat) > 0 )?'fa-solid fa-angle-right':''}}"></i></a></li>
                                    @if($last_count == 0)
                                    @php
                                    $last_product_counter = DB::table('product_category')->join('products','product_category.product_id','=','products.id')->where('product_category.category_id', $innervaluess->id)->count();
                                    $last_count_link = "<li><a href='javascript:;' data-menu-id='". $innervaluess->id ."' data-menu-slug='". $innervaluess->slug ."' class='searchCat'><div>". $innervaluess->name ."<span class='category_qty'>(".$last_product_counter.")</span></div></a></li>";
                                    $last_count = 1;
                                    @endphp
                                    @endif
                                    @endforeach
                                    {!! $last_count_link !!}
                                    
                                </ul>    
                                @endif
                            @endforeach
                            @endforeach
                            <!--//Fifth-->
                            @foreach($childcat as $innerchildkey => $valieInner)
                                @php
                                $childChilCat=  \DB::table('categories')->where('parent', $valieInner->id)->get();
                                @endphp
                                @foreach($childChilCat as $innerChildkey => $innerChildvaluess)
                                    @php
                                        
                                        $childSubSubcat = \DB::table('categories')->where('parent', $innerChildvaluess->id)->get();
                                    @endphp
                                    @if($childInnerSubcat != 0)
                                    <ul class="nazr hmenu hmenu-translateX-right" data-menu-id="{{$innerChildvaluess->id}}" data-parent-menu-id="1">
                                        <li class="back-option"><a href="javascript:;" data-menu-id="1"><div><i class="fa-solid fa-angle-left"></i> {{ $innerChildvaluess->name }} </div></a></li>
                                        @php
                                        $last_count = 0;
                                        $last_count_link = '';
                                        @endphp
                                        @foreach($childSubSubcat as $key => $innervalChildInner)
                                            @php
                                                $count = \DB::table('product_category')->where('category_id', $innervalChildInner->id)->count();
                                                $childInnerInnerSubcat = \DB::table('categories')->where('parent', $innervalChildInner->id)->get();
                                            @endphp
                                        <li><a href="javascript:;" data-menu-slug="{{$innervalChildInner->slug}}" data-menu-id="{{ $innervalChildInner->id }}" class="searchCat"><div>{{ $innervalChildInner->name }}<span class="category_qty">({{ $count }})</span></div><i class="{{(count($childInnerInnerSubcat) > 0 )?'fa-solid fa-angle-right':''}}"></i></a></li>
                                        @if($last_count == 0)
                                        @php
                                        $last_product_counter1 = DB::table('product_category')->join('products','product_category.product_id','=','products.id')->where('product_category.category_id', $innerChildvaluess->id)->count();
                                        $last_count_link1 = "<li><a href='javascript:;' data-menu-id='". $innerChildvaluess->id ."' data-menu-slug='". $innerChildvaluess->slug ."' class='searchCat'><div>". $innervaluess->name ."<span class='category_qty'>(".$last_product_counter1.")</span></div></a></li>";
                                        $last_count = 1;
                                        @endphp
                                        @endif
                                        @endforeach
                                        
                                        
                                    </ul>    
                                    @endif
                                @endforeach
                            @endforeach
                           
                            
                        </div>
                        <input type="hidden" name="category[]" />
                        
                        
                        <div class="filter_head mb-4">
                            <p class="black_heading30">Price</p>
                        </div>
                        <div>
                            <div class="slider-wrapper" id="priceRange_slider">
                                <input class="input-range" data-slider-id='ex12cSlider' type="text" data-slider-step="1"
                                    data-slider-value="{{ app('request')->input('min') != null ? app('request')->input('min') : '0' }}, {{ app('request')->input('max') != null ? app('request')->input('max') : '10000' }}"
                                    data-slider-min="0" data-slider-max="10000" data-slider-range="true"
                                    data-slider-tooltip_split="true" />
                            </div>
                            <ul class="min-max">
                                <li>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="text" class="form-control show-min" value="{{ app('request')->input('min') != null ? app('request')->input('min') : '0' }}">
                                    </div>
                                </li>
                                <li>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">$</span>
                                        </div>
                                        <input type="text" class="form-control show-max" value="{{ app('request')->input('max') != null ? app('request')->input('max') : '10000' }}">
                                    </div>
                                </li>
                            </ul>
                            <!--<input type="text" name=""-->
                            <!--    placeholder="${{ app('request')->input('min') != null ? app('request')->input('min') : '0' }} - ${{ app('request')->input('max') != null ? app('request')->input('max') : '0' }}"-->
                            <!--    class="priceRange_field" readonly>-->
                            <input type="hidden" name="min"
                                value="{{ app('request')->input('min') != null ? app('request')->input('min') : '0' }}"
                                id="min">
                            <input type="hidden" name="max"
                                value="{{ app('request')->input('max') != null ? app('request')->input('max') : '500' }}"
                                id="max">
                        </div>
                        <button class="gold_btn mt-5" type="submit">SEARCH</button>
                    </form>
                </div>
                <div class="shop_right">
                    <div class="featureProducts_wrap">
                        <div class="shop_Sec1_heading">
                            <p class="black_heading30 mb-0">
                                @if (count($req_cat_array) == 0)
                                    {{ $cat->name }}
                                @else
                                    {{ implode(', ', $req_cat_array) }}
                                @endif
                            </p>
                            @php
                            $is_discount = null;
                            $found = 0;
                            $gat_cat = \App\Category::where(['id' => $cat->id])->first();
                            $parent = $gat_cat->parent;
                            while($parent != 0){
                                if($gat_cat->has_discount != null){
                                    if($found == 0){
                                        $is_discount = '<p class="has_discount_category has_discount alert alert-info">'. ( $gat_cat->has_discount->type == 1 ? $gat_cat->has_discount->discount_price . ' Fixed Price' :  $gat_cat->has_discount->discount_price . '% OFF' ) .'</p>';
                                        $found = 1;
                                    }
                                }
                                $gat_cat = \App\Category::where(['id' => $parent])->first();
                                $parent = $gat_cat->parent;
                                
                            }
                            if($found == 0){
                                if($gat_cat->has_discount != null){
                                    $is_discount = '<p class="has_discount_category has_discount alert alert-info">'. ( $gat_cat->has_discount->type == 1 ? $gat_cat->has_discount->discount_price . ' Fixed Price' :  $gat_cat->has_discount->discount_price . '% OFF' ) .'</p>';
                                }
                            }
                            @endphp
                            @if($is_discount != null)
                            {!! $is_discount !!}
                            @endif
                            <!--@if($gat_cat->has_discount != null)-->
                            <!--<p class="has_discount_category has_discount alert alert-info">{{ $gat_cat->has_discount->type == 1 ? $gat_cat->has_discount->discount_price . ' Fixed Price' :  $gat_cat->has_discount->discount_price . '% OFF' }}</p>-->
                            <!--@endif-->
                            <span class="filter_btn">
                                <i class="fa-solid fa-sliders"></i>
                            </span>
                        </div>
                        <div class="row">
                            @foreach ($category as $key => $value)
                                <?php
                                $wishlist = App\wishlists::where('product_id', $value->id)
                                    ->where('user_id', Auth::user()->id)
                                    ->first();
                                    $att_model = \App\ProductAttribute::groupBy('attribute_id')->where('product_id', $value->id)->get();
                                   
                                
                                ?>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
                                    <div class="featureProduct">
                                        <div class="featureProduct_image">
                                            <img src="{{ asset($value->image) }}" alt="{{ $value->product_title }}"
                                                class="img-fluid">
                                            @php
                                                $now = new \DateTime();
                                                $newDate = $now->format('Y-m-d');
                                                $spanText = '';
                                                if ($value->discount_buy_qty > 0 && $value->discount_get_qty > 0) {
                                                    $spanText = `Buy ${$value->discount_buy_qty} get ${$value->discount_get_qty} free`;
                                                } elseif ($value->new_product == 1 && $value->new_product_date >= $newDate) {
                                                    $spanText = 'NEW';
                                                }
                                                $latItem = '';
                                                $secondlastItem = '';
                                                $saleItem = '';
                                                
                                                if($value->sales == 1){
                                                    $saleItem = 'SALE';
                                                }
                                            @endphp
                                            <!--if($value->stock == 1){-->
                                                <!--   $latItem = 'Last One'; -->
                                                <!--}-->
                                                <!--if($value->stock == 2){-->
                                                <!--   $secondlastItem = '2 LEFT'; -->
                                                <!--}      -->
                                            @if ($spanText != '')
                                            <!--<span class="lastProduct">{{ $spanText }}</span>-->
                                            <div class="yith-wcbm-badge yith-wcbm-badge-14198 yith-wcbm-badge-css yith-wcbm-badge--on-product-2154 yith-wcbm-badge-css-8 yith-wcbm-css-badge-14198 yith-wcbm-badge-14198" data-transform="">
                                            	<div class="yith-wcbm-badge__wrap">
                                            		<div class="yith-wcbm-css-s1 newBadgeBottom"></div>
                                            		<div class="yith-wcbm-css-s2 newBadgeCorner"></div>
                                            		<div class="yith-wcbm-css-text">
                                            			<div class="yith-wcbm-badge-text newBadge">
                                            				<p style="color: #ffffff"><span style="font-size: 10pt">{{ $spanText }}</span></p>
                                            			</div>
                                            		</div>
                                            	</div>
                                            	<!--yith-wcbm-badge__wrap-->
                                            	<svg> </svg>
                                            </div>       
                                            @endif
                                            @php
                                            $check_sale = 0;
                                            @endphp
                                            @if($saleItem != '')
                                            @php
                                            $check_sale = 1;
                                            @endphp
                                            <div class="yith-wcbm-badge yith-wcbm-badge-14191 yith-wcbm-badge-css yith-wcbm-badge--on-product-4746 yith-wcbm-badge-css-7 yith-wcbm-css-badge-14191 yith-wcbm-badge-14191" data-transform="">
                                            	<div class="yith-wcbm-badge__wrap">
                                            		<div class="yith-wcbm-css-s1"></div>
                                            		<div class="yith-wcbm-css-s2"></div>
                                            		<div class="yith-wcbm-css-text">
                                            			<div class="yith-wcbm-badge-text">
                                            				<p style="color: #ffffff">SALE!</p>
                                            			</div>
                                            		</div>
                                            	</div>
                                            	<!--yith-wcbm-badge__wrap-->
                                            	<svg> </svg>
                                            </div>
                                            @endif
                                            
                                            @if (($latItem != "") && ($check_sale == 0))
                                                <div class="yith-wcbm-badge yith-wcbm-badge-14198 yith-wcbm-badge-css yith-wcbm-badge--on-product-2154 yith-wcbm-badge-css-8 yith-wcbm-css-badge-14198 yith-wcbm-badge-14198" data-transform="">
                                                	<div class="yith-wcbm-badge__wrap">
                                                		<div class="yith-wcbm-css-s1"></div>
                                                		<div class="yith-wcbm-css-s2"></div>
                                                		<div class="yith-wcbm-css-text">
                                                			<div class="yith-wcbm-badge-text">
                                                				<p style="color: #ffffff"><span style="font-size: 10pt">Last One!</span></p>
                                                			</div>
                                                		</div>
                                                	</div>
                                                	<!--yith-wcbm-badge__wrap-->
                                                	<svg> </svg>
                                                </div>
                                            @endif
                                            @if (($secondlastItem != "") && ($check_sale == 0))
                                                <div class="yith-wcbm-badge yith-wcbm-badge-14525 yith-wcbm-badge-css yith-wcbm-badge--on-product-12281 yith-wcbm-badge-css-5 yith-wcbm-css-badge-14525 yith-wcbm-badge-14525" data-transform="">
                                                	<div class="yith-wcbm-badge__wrap">
                                                		<div class="yith-wcbm-css-s1"></div>
                                                		<div class="yith-wcbm-css-s2"></div>
                                                		<div class="yith-wcbm-css-text">
                                                			<div class="yith-wcbm-badge-text"><p style="color: #ffffff">2 LEFT!</p></div>
                                                		</div>
                                                	</div><!--yith-wcbm-badge__wrap-->
                                                	<svg>
                                                    
                                                </svg>
                                                </div>
                                            @endif
                                            @if(count($att_model) == 0)
                                            <form method="post" action="{{route('save_cart')}}" class="prdCart">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="product_id" id="product_id" value="{{ $value->id }}">
                                            <input type="hidden" max="10000" min="1" class="count" name="quantity" value="1" id="quantity-select" step="1">
                                                <a href="javascript:;" data-link="" class="add-cart" data-tooltip=" Add To Cart" data-prd="add-cart"
                                                    data-id="{{ $value->id }}"><i class="fa fa-shopping-cart"></i></a>
                                            </form>
                                            @else
                                            <a class="select-option" data-tooltip="Select Option" href="{{ route('shopDetail', ['slug' => $value->slug ]) }}"><i class="fa-solid fa-ellipsis-h"></i></a>
                                            @endif
                                            @if ($wishlist != '')
                                                <a href="{{ route('customer.wishlist.list') }}" data-link=""
                                                    class="quick-heart" data-tooltip=" View Wishlist"
                                                    data-id="{{ $value->id }}"><i class="fa fa-heart"></i></a>
                                            @else
                                                <a href="{{ route('wishlist.add', ['id' => $value->id]) }}"
                                                    data-link="" class="quick-heart" data-tooltip="Add To Wishlist"
                                                    data-id="{{ $value->id }}"><i class="fa-regular fa-heart"></i></a>
                                            @endif
                                            <a href="#"
                                                data-link="{{ route('shopDetail', ['slug' => $value->slug]) }}"
                                                data-tooltip="Quick View" class="quick-view"
                                                data-id="{{ $value->id }}"><i class="fa-regular fa-eye"></i></a>
                                            <a href="{{ route('shopDetail', ['slug' => $value->slug]) }}"
                                                class="shopNow_btn gold_btn">Shop Now</a>
                                        </div>
                                        <div class="featureProduct_detail">
                                            <div class="best-wrapper">
                                                <span>{{ $value->best_seller }}</span>
                                            </div>
                                            <p class="mb-3">{!! $value->product_title !!}</p>
                                            <p>
                                                {!! $value->getMinPrice() !!}
                                            </p>
                                            <!-- <p>$155.00 – $480.00</p> -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <nav class="shop_pagination" aria-label="...">
                {!! $category->appends(request()->input())->links() !!}
            </nav>
        </div>
    </section>

    <!-- section banner end -->
    <section class="d-none Inner_content pro wow fadeIn" data-wow-duration="2s" data-wow-delay="0.6s">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-3 col-md-3">
                    <div class="ls">
                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading bro">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapse99"> CATEGORIES </a>
                                    </h4>
                                </div>
                                <div id="collapse99" class="panel-collapse collapse">
                                    @foreach ($categories as $value)
                                        <div class="panel-group ">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a href="{{ url('category-detail/' . $value->id) }}">
                                                            {{ $value->name }} </a>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ls">
                        <h4>PRICE FILTER</h4>
                        <div class="cbtn">
                            <label class="newclick">£0.00 - £39.99 <input type="checkbox" checked="checked">
                                <span class="checkmark"></span>
                            </label>
                            <label class="newclick">£40.00 - £79.99 <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                            <label class="newclick">£40.00 - £79.99 <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                            <label class="newclick">£40.00 - £79.99 <input type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-9">
                    @if ($category)
                        @foreach ($category->chunk(3) as $product)
                            <div class="row Row_colm">
                                @foreach ($product as $value)
                                    <div class="col-xs-12 col-sm-6 col-md-4">
                                        <div class="Product_box wow zoomIn" data-wow-duration="2s" data-wow-delay="0.2s">
                                            <div class="imgbox">
                                                <a href="{{ url('shop-detail/' . $value->id) }}">
                                                    <img alt="" class="img-responsive"
                                                        src="{{ asset($value->image) }}">
                                                </a>
                                            </div>
                                            <div class="bottom_text">
                                                <h3>{{ $value->product_title }}</h3>
                                                <h4>
                                                    <del class="clr">{{ $value->price }}</del> {{ $value->price }}
                                                </h4>
                                                <a class="btn4 hvr-bounce-to-bottom"
                                                    href="{{ url('shop-detail/' . $value->id) }}">Add To Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <h1>No Product Found</h1>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--Footer Content Start-->
    <!-- ============================================================== -->
    <!-- BODY END HERE -->
    <!-- ============================================================== -->
@endsection
@section('css')
    <style>
    ul.min-max {
        list-style: none;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }
    
    ul.min-max li {
        flex: 0 0 46%;
    }
    
    ul.min-max li input {
        width: 100%;
        padding: 10px 18px;
        font-size: 17px;
        font-weight: 400;
        color: #000;
        background: #fff;
        border: 1px solid #838DA2;
        outline: none;
        font-family: 'Roboto', sans-serif;
        border-radius: 7px;
    }
    .min-max .input-group-text {
        height: 100%;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border: 1px solid #838DA2;
    }
        /*.quick-view:hover:before{*/
        /*  border: solid;*/
        /*  border-color: #444 transparent;*/
        /*  border-width: 12px 6px 0 6px;*/
        /*  content: "";*/
        /*  left: 45%;*/
        /*  bottom: 30px;*/
        /*  position: absolute;*/
        /*}*/
        /*.quick-heart:hover:before{*/
        /*  border: solid;*/
        /*  border-color: #444 transparent;*/
        /*  border-width: 12px 6px 0 6px;*/
        /*  content: "";*/
        /*  left: 45%;*/
        /*  bottom: 30px;*/
        /*  position: absolute;*/
        /*}*/
        @media only screen and (max-width:385px){
            .yith-wcbm-badge.yith-wcbm-badge-css.yith-wcbm-badge-14525 {
                left: 74% !important;
            }
        }
    </style>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.tag-button').click(function() {
                if ($('#tags').val() == '') {
                    $('#tags').parent().addClass('required');
                } else {
                    $('.filter_tags').append(
                        '<span class="filter_tag"><input type="hidden" name="tags[]" value="' + $(
                            '#tags').val() + '">' + $('#tags').val() +
                        ' <i class="fa-solid fa-xmark" onclick="removeTag(this)"></i></span>');
                    $('#tags').val('');
                }
            })
            $('.show_child').click(function(){
                var icon = $(this).find('i');
                if($(icon).hasClass('rotate-icon')){
                    $(icon).removeClass('rotate-icon')
                }else{
                    $(icon).addClass('rotate-icon')
                }
                var get_cat_selector = $(this).parent().parent().find('.child_sub_category_item');
                if($(get_cat_selector).hasClass('show_category_item')){
                    $(get_cat_selector).removeClass('show_category_item');
                }else{
                    $(get_cat_selector).addClass('show_category_item');
                }
            })
            $('.show_child_child').click(function(){
                var icon = $(this).find('i');
                if($(icon).hasClass('rotate-icon')){
                    $(icon).removeClass('rotate-icon')
                }else{
                    $(icon).addClass('rotate-icon')
                }
                var get_cat_selector = $(this).parent().find('.child_sub_child_category_item');
                if($(get_cat_selector).hasClass('show_category_item')){
                    $(get_cat_selector).removeClass('show_category_item');
                }else{
                    $(get_cat_selector).addClass('show_category_item');
                }
            })
            $('.add-cart').click(function(){
                
               var form =  $(this).parent().parent().find('.prdCart');
               form.submit();
              
            })
        });

        function removeTag(a) {
            $(a).parent().remove();
        }

        function clearAllTag() {
            $('.filter_tags').html('');
        }
        
    </script>
    <script>
        $(document).ready(function(){
            $('.categories').find('.first-level').each(function(){
                var total = 0;
                $(this).find('.category_item').each(function(){
                    if($(this).hasClass('category_item_not')){
                        
                    }else{
                        var inner_total = $(this).find('.category_qty').text();
                        
                        inner_total = inner_total.replace(/\(/g, '');
                        inner_total = inner_total.replace(/\)/g, '');
                        total += parseInt(inner_total);
                    }
                })
                $(this).find('.category_qty_main').text('(' + total + ')');
            })
        })
    </script>
    
    <script>
        $(document).ready(function(){
            $('.hmenu a').click(function(e){
                e.preventDefault();
                var menu_id = $(this).data('menu-id');
                
                var next_menu = $('ul[data-menu-id="'+menu_id+'"]')
               
                if($(next_menu).length != 0){
                    console.log($(this).parent().parent());
                    $(this).parent().parent().removeClass('hmenu-visible');
                    $(this).parent().parent().addClass('hmenu-translateX-left');
                    
                    $(next_menu).removeClass('hmenu-translateX-left');
                    $(next_menu).removeClass('hmenu-translateX-right');
                    $(next_menu).addClass('hmenu-visible hmenu-translateX');
                }
            })
        })
        
        $('.searchCat').click(function(){
            var slug = $(this).data('menu-slug');
            var cat_url = "{{ route('categoryDetail', ":slug") }}";
            cat_url = cat_url.replace(':slug', slug);
            window.location.href = cat_url;
            // id = $(this).data('menu-id');
            // $('input[name="category[]"]').val(id);
            // $('.subCatForm').submit();
        });
    </script>
@endsection
