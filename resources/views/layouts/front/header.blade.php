<?php $segment = Request::segments(); ?>


<header>
    <div class="header_topBar">
        <div class="container">
            <div class="topBar_wrap">
                <div class="leftCol">
                    <div class="social_icons_wrap">
                        <p>Follow Us</p>
                        <div class="social_icons">
                            @if (App\Http\Traits\HelperTrait::returnFlag(682) != null)
                                <a href="{{ App\Http\Traits\HelperTrait::returnFlag(682) }}" target="_blank">
                                    <img src="{{ asset('images/facebook-logo.png') }}" alt="">
                                </a>
                            @endif
                            @if (App\Http\Traits\HelperTrait::returnFlag(1960) != null)
                                <a href="{{ App\Http\Traits\HelperTrait::returnFlag(1960) }}" target="_blank"
                                    class="color">
                                    <i class="fa-brands fa-twitter"></i></a>
                            @endif
                            @if (App\Http\Traits\HelperTrait::returnFlag(1962) != null)
                                <a href="{{ App\Http\Traits\HelperTrait::returnFlag(1962) }}" target="_blank">
                                    <img src="{{ asset('images/instagram-logo.png') }}" alt="">
                                </a>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('search.product') }}" method="get">
                        <div class="searchBar">
                            <input type="search" name="q" placeholder="Search part # or keywords" required="">
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div class="rightCol">
                    @if (auth()->check())
                        <a href="{{ route('account') }}">
                            <i class="fa-solid fa-user"></i>
                            {{ Auth::user()->name }}
                        </a>
                    @else
                        <a href="{{ route('signin') }}">
                            <i class="fa-solid fa-user"></i>
                            <span>Login/Register</span>
                        </a>
                    @endif
                    <a href="{{ route('customer.wishlist.list') }}" class="quick-heart" data-tooltip="Wishlist">
                        <i class="fa-solid fa-heart"></i>
                    </a>
                    
                    <a data-link="" href="javascript:;" id="cart" class="cart-menu quick-heart" data-tooltip="Cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>{{ Session::get('cart') != null ? COUNT(Session::get('cart')) : 0 }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="shopping-cart">
        <div class="shopping-cart-header">
            <a href="{{ route('cart') }}">
                <i class="fa fa-shopping-cart cart-icon"></i>
                <span class="badge">{{ Session::get('cart') != null ? COUNT(Session::get('cart')) : 0 }}</span>
            </a>
            @php
            $subtotal = 0;
            @endphp
            @foreach($cart as $key => $value)
            @php
				$subtotal += ($value['baseprice'] == 0 ? $value['variation_price'] : $value['baseprice']) * $value['qty'];
			@endphp
			@endforeach
            <div class="shopping-cart-total">
                <span class="lighter-text">Total:</span>
                <span class="main-color-text">${{$subtotal - (Session::has('discount') ? Session::get('discount')['price'] : 00.00) }}</span>
            </div>
        </div>
        @php
        $cart = Session::get('cart');
        @endphp
        @if(($cart == null) || (count($cart) == 0))
        <div class="cart-empty alert alert-danger">
            <p>No Product's in your Cart</p>
        </div>
        @else
        <ul class="shopping-cart-items">
            @php
            $subtotal = 0;
            @endphp
            @foreach($cart as $key => $value)
            @php
				if($value['variation'] == null){
					$image = asset($value['image']);
				}else{
					$image = $value['variation'][$var_id]['image'];
					if($image == null){
						$image = asset($value['image']);
					}else{
						$image = asset($value['variation'][$var_id]['image']);
					}
				}
				$subtotal += ($value['baseprice'] == 0 ? $value['variation_price'] : $value['baseprice']) * $value['qty'];
			@endphp
            <li class="clearfix">
                <a href="{{ route('remove_cart',['id' => $value['cart_id']]) }}" class="header-cart-cross">
            		<i class="fa-solid fa-xmark"></i>
            	</a>
                <img src="{{ $image }}" alt="item1">
                <span class="item-name"><a href="{{ route('shopDetail', ['id' => $value['id'], 'slug' => str_slug($value['name'] , '-')]) }}">{{ $value['name'] }}</a></span>
                <span class="item-price">${{ $value['baseprice'] == 0 ? $value['variation_price'] : $value['baseprice'] }}</span>
                <span class="item-quantity">Quantity: {{ $value['qty'] }}</span>
            </li>
            @endforeach
        </ul>
        <a href="{{ route('checkout') }}" class="button gold_btn">Checkout</a>
        @endif
    </div>
    <!-- Mobile Menu Start -->
    <div class="mobile_menu">
        <div class="container">
            <div class="stellarnav left mobile ">
                @php
                    $data = DB::table('categories')
                        ->where('parent', 0)
                        ->where('id', '!=', 1)
                        ->where('id','!=',215)
                        ->where('status', 1)
                        ->get();
                @endphp
                <ul>
                    <li class="megaMenu">
                        <a href="{{ route('home') }}">Home</a>
                        <ul>
                            <li><a href="{{route('firing.process')}}">The Firing Process</a></li>
                            <li><a href="{{route('jewelry.care')}}">Jewelry Care</a></li>
                            <li><a href="{{route('faq')}}">FAQ</a></li>
                            @php
                                $cat = DB::table('categories')->where('id',215)->first();
                            @endphp
                            <li><a class="dropdown-item" href="{{route('categoryDetail', ['slug' => str_slug($cat->slug, '-')])}}">{{$cat->name}}</a></li>
                        </ul>
                    </li>
                    @foreach ($data as $key => $value)
                      
                        @php
                            $subcat = DB::table('categories')
                                ->where('parent', $value->id)
                                ->where('status', 1)
                                ->get();
                                
                            $directproducts = DB::table('product_category')
                                ->where('category_id', $value->id)
                                ->count();
                                
                        @endphp

                        @if ($directproducts > 0 || count($subcat) > 0)
                            <li class="{{ count($subcat) != 0 ? 'megaMenu' : '' }}"><a
                                    href="{{ $directproducts > 0 ? route('categoryDetail', ['slug' => str_slug($value->slug, '-')]) : 'javascript:void(0)' }}">{{ str_replace('Catalog', '', $value->name) }}</a>
                                @if (count($subcat) > 0)
                                    <ul>
                                        @foreach ($subcat as $subkey => $subvalue)
                                            @php
                                                $directsubvalueproducts = DB::table('product_category')
                                                    ->where('category_id', $subvalue->id)
                                                    ->count();
                                            @endphp
                                            <li>
                                                <a
                                                    href="{{ $directsubvalueproducts > 0 ? route('categoryDetail', ['slug' => str_slug($subvalue->slug, '-')]) : 'javascript:void(0)' }}">
                                                    {{ $subvalue->name }}
                                                </a>
                                                @php
                                                    $childcat = DB::table('categories')
                                                        ->where('parent', $subvalue->id)->orderBy('name', 'ASC')
                                                        ->get();
                                                @endphp
                                                @if (count($childcat) != 0)
                                                    <ul>
                                                        @foreach ($childcat as $childkey => $childvalue)
                                                            @php
                                                                $innerchild = DB::table('categories')
                                                                    ->where('parent', $childvalue->id)
                                                                    ->get();
                                                                $directchildvalueproducts = DB::table('product_category')
                                                                    ->where('category_id', $childvalue->id)
                                                                    ->count();
                                                            @endphp
                                                            <li>
                                                                <a
                                                                    href="{{ $directchildvalueproducts > 0 ? route('categoryDetail', ['slug' => str_slug($childvalue->slug, '-')]) : 'javascript:void(0)' }}">
                                                                    {{ $childvalue->name }}
                                                                </a>
                                                                
                                                                @php
                                                                $chilInnerCat = DB::table('categories')
                                                                    ->where('parent', $childvalue->id)->orderBy('name', 'ASC')
                                                                    ->get();
                                                                @endphp
                                                               
                                                                @if(count($chilInnerCat) != 0)
                                                                    <ul>
                                                                        @foreach ($chilInnerCat as $childInnerkey => $childInnerValue)
                                                                            @php
                                                                                $directchildInnerValueproducts = DB::table('product_category')
                                                                                ->where('category_id', $childInnerValue->id)
                                                                                ->count();
                                                                            @endphp
                                                                            <li>
                                                                                <a
                                                                                    href="{{ $directchildInnerValueproducts > 0 ? route('categoryDetail', ['slug' => str_slug($childInnerValue->slug, '-')]) : 'javascript:void(0)' }}">
                                                                                    {{ $childInnerValue->name }}
                                                                                </a>
                                                                                @php
                                                                                    $chilInnerSubCat = DB::table('categories')
                                                                                        ->where('parent', $childInnerValue->id)->orderBy('name', 'ASC')
                                                                                        ->get();
                                                                                @endphp
                                                                                @if(count($chilInnerSubCat) != 0)
                                                                                    <ul>
                                                                                        @foreach($chilInnerSubCat as $subCatkey => $subSubCat)
                                                                                            @php
                                                                                                $directchildSubValueproducts = DB::table('product_category')
                                                                                                ->where('category_id', $subSubCat->id)
                                                                                                ->count();
                                                                                            @endphp    
                                                                                            <li>
                                                                                                <a
                                                                                                    href="{{ $directchildSubValueproducts > 0 ? route('categoryDetail', ['slug' => str_slug($subSubCat->slug, '-')]) : 'javascript:void(0)' }}">
                                                                                                    {{ $subSubCat->name }}
                                                                                                </a>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif
                                                                                
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                                
                                                            </li>
                                                            
                                                            
                                                            
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                            </li>
                        @endif
                    @endforeach
                    <li><a href="{{ route('custom.orders') }}">Custom Order</a></li>
                    <li><a href="{{ route('testimonial') }}">Testimonials</a></li>
                    <li><a href="{{ route('our.story') }}">Our Story</a></li>
                    <li><a href="{{ route('schedule') }}">Schedule</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Mobile Menu End -->
    <div class="header_middle" style="background: {{ App\Http\Traits\HelperTrait::returnFlag(1977) }}">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3 mb-4">
                    <div class="logo" data-aos="fade-down" data-aos-duration="1200">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset($logo->img_path) }}" alt="logo" class="img-fluid">
                        </a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <div class="header_text">
                        <p>{!! App\Http\Traits\HelperTrait::returnFlag(1967) !!}</p>
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-3 mb-4">
                    <div class="header_col3">
                        <p class="header_smallText">{{ App\Http\Traits\HelperTrait::returnFlag(1968) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Desktop Menu Start -->
    <div class="navigationBar">
        <div class="container-fluid">
            <div class="">
                <ul>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="{{ route('home') }}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Home</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{route('firing.process')}}">The Firing Process</a>
                            <a class="dropdown-item" href="{{route('jewelry.care')}}">Jewelry Care</a>
                            <a class="dropdown-item" href="{{route('faq')}}">FAQ</a>
                            @php
                                $cat = DB::table('categories')->where('id',215)->first();
                            @endphp
                            <a class="dropdown-item" href="{{route('categoryDetail', ['slug' => str_slug($cat->slug, '-')])}}">{{$cat->name}}</a>
                        </div>
                     </li>
                    

                    @php
                        $data = DB::table('categories')
                            ->where('parent', 0)
                            ->where('id', '!=', 1)
                            ->where('id','!=',215)
                            ->where('status', 1)
                            ->get();
                            
                    @endphp
                    @foreach ($data as $key => $value)
                        @php
                            $subcat = DB::table('categories')
                                ->where('parent', $value->id)
                                ->where('status', 1)
                                ->get();
                            $directproducts = DB::table('product_category')
                                ->where('category_id', $value->id)
                                ->count();
                        @endphp
                        @if ($directproducts > 0 || count($subcat) > 0)
                            <li class="{{ count($subcat) != 0 ? 'megaMenu' : '' }}"><a
                                    href="{{ $directproducts > 0 ? route('categoryDetail', ['slug' => str_slug($value->slug, '-')]) : 'javascript:void(0)' }}">{{ str_replace('Catalog', '', $value->name) }}
                                    {!! count($subcat) != 0 ? '<i class="fa-solid fa-chevron-down"></i>' : '' !!}</a>
                        @endif


                        @if (count($subcat) != 0)
                            <div class="megaMenu_wrap" id="mega_menu">
                                <div class="mega_leftCol">
                                    @foreach ($subcat as $subkey => $subvalue)
                                        <div class="mega_item">
                                            <h5 class="sub_itemTitle">
                                                @php
                                                    $directsubvalueproducts = DB::table('product_category')
                                                        ->where('category_id', $subvalue->id)
                                                        ->count();
                                                @endphp
                                                <a
                                                    href="{{ $directsubvalueproducts > 0 ? route('categoryDetail', ['slug' => str_slug($subvalue->slug, '-')]) : 'javascript:void(0)' }}">
                                                    {{ $subvalue->name }}
                                                </a>
                                            </h5>
                                            @php
                                                $childcat = DB::table('categories')
                                                    ->where('parent', $subvalue->id)
                                                    ->orderBy('order_category', 'asc')
                                                    ->get();
                                            @endphp
                                            @if (count($childcat) != 0)
                                                <div class="collapse sub_items" id="Collapse01">
                                                    @foreach ($childcat as $childkey => $childvalue)
                                                        @php
                                                            $innerchild = DB::table('categories')
                                                                ->where('parent', $childvalue->id)
                                                                ->get();
                                                            $directchildvalueproducts = DB::table('product_category')
                                                                ->where('category_id', $childvalue->id)
                                                                ->count();
                                                        @endphp
                                                        @if (count($innerchild) != 0)
                                                            <a href="{{ $directchildvalueproducts > 0 && $childvalue->slug != 'gift-certificate'? route('categoryDetail', ['slug' => str_slug($childvalue->slug, '-')]) : 'javascript:void(0)' }}"
                                                                role="button" class="sub-child" aria-expanded="false"
                                                                aria-controls="Collapse_sub_{{ $childvalue->id }}">{{ $childvalue->name }}</a>
                                                            <div class="collapse sub_items sub_items_show"
                                                                id="Collapse_sub_{{ $childvalue->id }}">

                                                                @foreach ($innerchild as $innerkey => $innervalue)
                                                                
                                                                    @php
                                                                        $innerInnerChild = DB::table('categories')
                                                                                    ->where('parent', $innervalue->id)
                                                                                    ->get();
                                                                        $directinnervalueproducts = DB::table('product_category')
                                                                            ->where('category_id', $innervalue->id)
                                                                            ->count();
                                                                    @endphp
                                                                    @if(count($innerInnerChild) != 0)
                                                                    <a href="javascript:void(0)">{{ $innervalue->name }}</a>
                                                                    <div class="collapse sub_items sub_items_show second-second-child"
                                                                                id="Collapse_sub_{{ $childvalue->id }}">
                                                                        @foreach ($innerInnerChild as $innerInnerkey => $innerInnervalue)
                                                                         @php
                                                                            $directinnerInnervalueproducts = DB::table('product_category')
                                                                            ->where('category_id', $innerInnervalue->id)
                                                                            ->count();
                                                                         @endphp
                                                                         <a href="{{ $directinnerInnervalueproducts > 0 ? route('categoryDetail', ['slug' => str_slug($innerInnervalue->slug, '-')]) : 'javascript:void(0)' }}">{{ $innerInnervalue->name }}</a>
                                                                        @endforeach
                                                                        
                                                                    </div>
                                                                    @else
                                                                     <a
                                                                        href="{{ route('categoryDetail', ['slug' => str_slug($innervalue->slug, '-')]) }}">{{ $innervalue->name }}</a>
                                                                    
                                                                    @endif
                                                                
                                                                
                                                                
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <a
                                                                href="{{ route('categoryDetail', ['slug' => str_slug($childvalue->slug, '-')]) }}">{{ $childvalue->name }}</a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if ($value->is_nested == 0)
                                        <div class="mega_image">
                                            <img src="{{ asset($value->menu_image) }}" alt=""
                                                class="img-fluid">
                                        </div>
                                    @endif
                                </div>
                                <div class="mega_rightCol">
                                    <h5 class="sub_itemTitle">Featured Items</h5>
                                    <div class="megaMenu_slider_wrap">
                                        <div class="owl-carousel megaMenu_slider owl-theme">
                                            @php
                                                $pro = App\Product::where('is_featured_menu', $value->id)->get();
                                            @endphp
                                            @foreach ($pro as $pro_key => $pro_value)
                                                <div class="item">
                                                    <div class="featureProduct">
                                                        <div class="featureProduct_image">
                                                            <img src="{{ asset($pro_value->image) }}" alt="product"
                                                                class="img-fluid">
                                                            <a href="{{ route('shopDetail', ['id' => $pro_value->id, 'slug' => str_slug($pro_value->product_title, '-')]) }}"
                                                                class="shopNow_btn gold_btn">Shop Now</a>
                                                        </div>
                                                        <div class="featureProduct_detail">
                                                            <p class="mb-3">{!! $pro_value->product_title !!}</p>
                                                            <p>{!! $pro_value->getMinPrice() !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if ($value->is_nested == 1)
                                        <div class="mega_image">
                                            <img src="{{ asset($value->menu_image) }}" alt=""
                                                class="img-fluid">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        </li>
                    @endforeach
                    
                    <li><a href="{{ route('custom.orders') }}">Custom Order</a></li>
                    <li><a href="{{ route('testimonial') }}">Testimonials</a></li>
                    <li><a href="{{ route('our.story') }}">Our Story</a></li>
                    <li><a href="{{ route('schedule') }}">Schedule</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Desktop Menu End -->
</header>
