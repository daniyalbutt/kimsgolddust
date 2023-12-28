@extends('layouts.main')
@section('title', $page->seo_title)
@section('description', $page->seo_description)
@section('keywords', $page->seo_keyword)
@if($page->og_title !="")
@section('og_title',$page->og_title)
@else
@section('og_title',$page->seo_title)
@endif
@if($page->og_description !="")
@section('og_description',$page->og_description)
@else
@section('og_description',$page->seo_description)
@endif




@section('content')
    <section class="banner_sec">
        <div class="owl-carousel banner_slider owl-theme">
            @foreach ($banners as $key => $value)
                <div class="item" style="background: url({{ asset($value->image) }}) no-repeat;">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6 col-lg-6 mb-4">
                                <div class="leftCol {{ $value->additonal_class }}" data-aos="fade-right"
                                    data-aos-duration="1200">
                                    @if ($value->front_image != null)
                                        <img src="{{ asset($value->front_image) }}" alt="" class="img-fluid">
                                    @endif
                                    <h1>{!! $value->title !!}</h1>
                                    @if ($value->shop_link != null)
                                        <div class="banner_btn">
                                            <a href="{{ $value->shop_link }}" class="black_btn">Shop Now</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 mb-4">
                                <div class="rightCol">
                                    @if ($value->right_image != null)
                                        <img src="{{ asset($value->right_image) }}" alt="bann_img" class="img-fluid">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="index_sec2">
        <div class="owl-carousel product_slider owl-theme">

            @foreach ($categories as $key => $value)
                @php
                    $directproducts = DB::table('product_category')
                        ->where('category_id', $value->id)
                        ->count();
                @endphp

                <div class="item">
                    <div class="slide_box">
                        <img src="{{ asset($value->image) }}" alt="product_img" class="img-fluid">
                        <p>{{ $value->name }}</p>
                        <a href="{{ route('categoryDetail', ['id' => $value->id, 'slug' => str_slug($value->name, '-')]) }}"
                            class="see_collect">See Collections</a>
                        <div class="slideBox_btn text-center">
                            <a href="{{ route('categoryDetail', ['id' => $value->id, 'slug' => str_slug($value->name, '-')]) }}"
                                class="gold_btn">See Collections</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="index_sec3" style="background-image: url({{ asset($page->image) }});">
        <div class="container">
            <div class="indexSec3_content" data-aos="fade-right" data-aos-duration="1200">
                {!! $page->content !!}
                <div class="indexSec3_btn mt-2">
                    <a href="{{ $section[2]->value }}" class="gold_btn">{{ $section[1]->value }}</a>
                </div>
            </div>
        </div>
        <img src="{{ asset($section[0]->value) }}" alt="{{ $section[0]->alter_tag }}" class="img-fluid" data-aos="fade-up" data-aos-duration="1200">
    </section>

    <section class="index_sec5">
        <div class="container">
            <p class="section_heading text-center" data-aos="fade-down" data-aos-duration="1200">{{ $section[3]->value }}
            </p>
            <div class="featureProducts_wrap">
                <div class="row">
                    @foreach ($featured as $key => $value)
                        <?php
                        $wishlist = App\wishlists::where('product_id', $value->id)
                            ->where('user_id', Auth::user()->id)
                            ->first();
                        $att_model = \App\ProductAttribute::groupBy('attribute_id')->where('product_id', $value->id)->get();
                       
                        ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="featureProduct">
                                <div class="featureProduct_image">
                                    <img src="{{ asset($value->image) }}" alt="{{ $value->product_title }}"
                                        class="img-fluid">
                                        @php
                                            $now = new \DateTime();
                                            $newDate = $now->format('Y-m-d');
                                            $spanText = "";
                                            if($value->discount_buy_qty > 0 && $value->discount_get_qty > 0)
                                            {   
                                                $spanText = `Buy ${$value->discount_buy_qty} get ${$value->discount_get_qty} free`;

                                            }
                                            
                                            else if($value->new_product == 1 && $value->new_product_date >= $newDate){
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
                                    @if ($spanText != "")
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
                                    @if ($latItem != "")
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
                                    @if ($secondlastItem != "")
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
                                    @if($saleItem != '')
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
                                    @if(count($att_model) == 0)
                                    <form method="post" action="{{route('save_cart')}}" class="prdCart">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="product_id" id="product_id" value="{{ $value->id }}">
                                        <input type="hidden" max="10000" min="1" class="count" name="quantity" value="1" id="quantity-select" step="1">
                                        <a href="javascript:;" data-link="" class="add-cart" data-tooltip=" Add To Cart" data-prd="add-cart"
                                            data-id="{{ $value->id }}"><i class="fa fa-shopping-cart"></i></a>
                                        </form>
                                    @else
                                        <a class="select-option" data-tooltip="Select Option" href="{{ route('shopDetail', ['slug' => $value->slug]) }}"><i class="fa-solid fa-ellipsis-h"></i></a>
                                    @endif
                                    @if ($wishlist != '')
                                        <a href="{{ route('customer.wishlist.list') }}" data-link="" class="quick-heart"
                                            data-tooltip="View Wishlist" data-id="{{ $value->id }}"><i
                                                class="fa fa-heart"></i></a>
                                    @else
                                        <a href="{{ route('wishlist.add', ['id' => $value->id]) }}" data-link=""
                                            class="quick-heart" data-tooltip="Add To Wishlist"
                                            data-id="{{ $value->id }}"><i class="fa-regular fa-heart"></i></a>
                                    @endif
                                    <a href="#"
                                        data-link="{{ route('shopDetail', ['slug' => $value->slug]) }}"
                                        data-tooltip="Quick View" class="quick-view" data-id="{{ $value->id }}"><i
                                            class="fa-regular fa-eye"></i></a>
                                    <a href="{{ route('shopDetail', ['slug' => $value->slug]) }}"
                                        class="shopNow_btn gold_btn">Shop Now</a>
                                </div>
                                <div class="featureProduct_detail">
                                    <div class="best-wrapper">
                                        <span>{{ $value->best_seller }}</span>
                                    </div>
                                    <p class="mb-3">{{ $value->product_title }}</p>
                                    <p>{!! $value->getMinPrice() !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="index_sec6">
        <div class="container">
            <div class="row justify-content-center">
                @foreach ($productvideos as $key => $value)
                @php
                $pro = DB::table('products')->where('id', $value->product_id)->first();
                @endphp
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                        <a href="{{ route('shopDetail', ['slug' => $pro->slug]) }}">
                            <div class="singleProduct">
                                <video autoplay muted loop>
                                    <source src="{{ asset($value->video) }}" type="video/mp4">
                                </video>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="index_sec7" style="background-image: url({{ asset($section[4]->value) }});">
        <div class="container">
            <div class="indexSec7_content" data-aos="fade-down" data-aos-duration="1200">
                {!! $section[5]->value !!}
                <div class="indexSec3_btn mt-2">
                    <a href="{{ $section[7]->value }}" class="gold_btn">{{ $section[6]->value }}</a>
                </div>
            </div>
        </div>
        <img src="{{ asset($section[8]->value) }}" alt="{{ $section[8]->alter_tag }}" class="img-fluid element1">
    </section>

    <section class="testimonial_sec">
        <div class="container">
            <p class="section_heading" data-aos="fade-down" data-aos-duration="1200">{{ $section[9]->value }}</p>
            <div class="testimonialSlider_wrap">
                <div class="owl-carousel testimonials_slider owl-theme">
                    @foreach ($testimonials as $key => $value)
                        <div class="item">
                            <div class="review_card">
                                <div>
                                    <span><i class="fa-solid fa-quote-left"></i></span>
                                    {!! $value->comments !!}
                                </div>
                                <div class="review_card_footer">
                                    <div class="client_image">
                                        <img src="{{ asset($value->image) }}" alt="client" class="img-fluid">
                                    </div>
                                    <div class="client_review">
                                        <p>{{ $value->name }}</p>
                                        <div class="client_rating">
                                            @for ($i = 0; $i < $value->stars; $i++)
                                                <i class="fa-sharp fa-solid fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('testimonial') }}" class="gold_btn">View More</a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('css')
    <style>

    </style>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.add-cart').click(function(){
                
               var form =  $(this).parent().parent().find('.prdCart');
               form.submit();
              
            }) 
        });
    </script>
@endsection
