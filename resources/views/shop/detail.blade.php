@extends('layouts.main') @section('title', $product_detail->seo_title != null ? $product_detail->seo_title :
    $product_detail->product_title) @section('description', $product_detail->seo_description) @section('keywords',
    $product_detail->seo_keyword) @section('additional_seo') {!! $product_detail->additional_seo !!} @endsection @section('content')
    <!-- ============================================================== -->
    <!-- BODY START HERE -->
    <!-- ============================================================== -->
    <section class="inner_banner">
        <div class="container">
            <div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">
                <h1>Product Details</h1>
                <p>Home / Product Details</p>
            </div>
        </div>
    </section>
    <section class="proDetail_sec2">
        <div class="container">
            <div class="row">

                @php
                    $total_images = 0;
                    $images = DB::table('product_imagess')
                        ->where('product_id', $product_detail->id)
                        ->where('is_variant', 0)
                        ->get();
                    $total_images = $total_images + count($images);
                @endphp
                <div class="col-12 col-md-5 col-lg-5 mb-5">
                    <div id="sync1" class="owl-carousel owl-theme">
                        <div class="item">
                            <div class="prodcutDetail_image-wrapper prodcutDetail_image">
                                <a href="{{ asset($product_detail->image) }}" data-fancybox="gallery">
                                    <img src="{{ asset($product_detail->image) }}" alt="" class="img-fluid">
                                </a>
                            </div>
                        </div>
                        @foreach ($images as $key => $value)
                            <div class="item">
                                <div class="prodcutDetail_image-wrapper prodcutDetail_image">
                                    <a href="{{ asset($value->image) }}" data-fancybox="gallery">
                                        <img src="{{ asset($value->image) }}" alt="" class="img-fluid">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        @foreach ($att_model as $att_models)
                            @php
                                $pro_att = \App\ProductAttribute::where(['attribute_id' => $att_models->attribute_id, 'product_id' => $product_detail->id])->orderBy('order_id', 'asc')->get();
                            @endphp
                            @foreach ($pro_att as $pro_atts)
                                @if ($pro_atts->image != null)
                                    <div class="item">
                                        <div class="prodcutDetail_image-wrapper prodcutDetail_image">
                                            <a href="{{ asset($pro_atts->image) }}" data-fancybox="gallery">
                                                <img src="{{ asset($pro_atts->image) }}" alt="" class="img-fluid">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        @php
                        $product_videos = DB::table('product_videos')->where('product_id', $product_detail->id)->first();
                        @endphp
                        @if($product_videos != null)
                        <div class="item">
                            <div class="prodcutDetail_image-wrapper prodcutDetail_image">
                                <video autoplay muted loop>
                                    <source src="{{ asset($product_videos->video) }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div id="sync2" class="owl-carousel owl-theme">
                        <div class="item">
                            <div class="prodcutDetail_image-wrapper-icon prodcutDetail_image">
                                <img src="{{ asset($product_detail->image) }}" alt="" class="img-fluid">
                            </div>
                        </div>
                        @foreach ($images as $key => $value)
                            <div class="item">
                                <div class="prodcutDetail_image-wrapper-icon prodcutDetail_image">
                                    <img src="{{ asset($value->image) }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        @endforeach
                        @foreach ($att_model as $att_models)
                            @php
                                $pro_att = \App\ProductAttribute::where(['attribute_id' => $att_models->attribute_id, 'product_id' => $product_detail->id])->orderBy('order_id', 'asc')->get();
                            @endphp
                            @foreach ($pro_att as $pro_atts)
                                @if ($pro_atts->image != null)
                                    <div class="item">
                                        <div class="prodcutDetail_image-wrapper-icon prodcutDetail_image">
                                            <img src="{{ asset($pro_atts->image) }}" alt="" class="img-fluid">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                        @if($product_videos != null)
                        <div class="item">
                            <div class="prodcutDetail_image-wrapper-icon prodcutDetail_image">
                                <video autoplay muted loop>
                                    <source src="{{ asset($product_videos->video) }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @php
                    $cat_listing = [];
                    $has_discount = null;
                    $found = 0;
                @endphp
                @foreach ($product_detail->category_list as $key => $cat)
                @php
                array_push($cat_listing, $cat->name);
                @endphp
                @endforeach
                @if($product_detail->price == 0.00)
                @foreach ($product_detail->category_list as $key => $cat)
                    @php
                        if($cat->has_discount != null){
                            if($found == 0){
                                $has_discount = '<p class="has_discount alert alert-info">'. ( $cat->has_discount->type == 1 ? $cat->has_discount->discount_price . ' Fixed Price' :  $cat->has_discount->discount_price . '% OFF') .'</p>';
                                $found = 1;
                            }
                        }else{
                        
                            $gat_cat = \App\Category::where(['id' => $cat->id])->first();
                            $parent = $gat_cat->parent;
                            while($parent != 0){
                                $gat_cat = \App\Category::where(['id' => $parent])->first();
                                $parent = $gat_cat->parent;
                            }
                            if($gat_cat->has_discount != null){
                                if($found == 0){
                                    $has_discount = '<p class="has_discount alert alert-info">'. ( $gat_cat->has_discount->type == 1 ? $gat_cat->has_discount->discount_price . ' Fixed Price' :  $gat_cat->has_discount->discount_price . '% OFF') .'</p>';
                                    $found = 1;
                                }
                            }
                        }
                    @endphp
                @endforeach
                @endif
                @php
                @endphp
                <div class="col-12 col-md-7 col-lg-7">
                    <form method="post" action="{{ route('save_cart') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="product_id" id="product_id" value="{{ $product_detail->id }}">
                        <div class="product-details">
                            <div class="detail-text">
                                <h2 class="black_heading40 mb-4">{!! $product_detail->product_title !!}</h2>
                                <div class="main-price-wrapper">
                                    <div class="productPrice_rating">
                                      
                                            <h6 id="price-show"><strong></strong><span>{!! $product_detail->getMinPrice() !!}</span></h6>
                                     
                                    </div>
                                    @if($has_discount != null)
                                    {!! $has_discount !!}
                                    @endif
                                </div>
                                <div class="short-description">
                                    @php
                                        $short_description = str_replace(["\r\n", "\r", "\n"], "<span class='spacing'></span>", $product_detail->short_description);
                                    @endphp

                                    <p>{!! str_replace('\n', '', $short_description) !!}</p>
                                    <hr>
                                </div>
                                <div class="stock-info">
                                    @if (count($att_model) == 0)
                                        <p><i class="fa-solid fa-circle-check"></i> {{ $product_detail->stock }} in stock
                                        </p>
                                    @endif
                                </div>
                                @if ($product_detail->discount_buy_qty > 0 && $product_detail->discount_get_qty > 0)
                                    <h5 class="gold_btn">Buy {{ $product_detail->discount_buy_qty }} get
                                        {{ $product_detail->discount_get_qty }} free!</h5>
                                @endif
                                @php
                                    $starter = 0;
                                @endphp
                                @foreach ($att_model as $att_models)
                                    <div class="variation product_color mt-4">
                                        <p class="paragraph mb-2">{{ $att_models->attribute->name }}</p>
                                        @php
                                            $pro_att = \App\ProductAttribute::where(['attribute_id' => $att_models->attribute_id, 'product_id' => $product_detail->id])->orderBy('order_id','asc')->get();
                                        @endphp

                                        <select class="variation-select"
                                            name="variation[{{ $att_models->attribute->name }}]" required>
                                            <option data-stock="" value="" data-select="0">Choose an option</option>
                                            @foreach ($pro_att as $pro_atts)
                                                <option data-stock="{{ $pro_atts->qty }}"
                                                    data-id="{{$pro_atts->id}}" data-price="{{ $pro_atts->price }}"
                                                    data-regular_price="{{ $pro_atts->regular_price }}"
                                                    value="{{ $pro_atts->value }}" data-image="{{ $pro_atts->image }}"
                                                    data-select="{{ ++$total_images }}">{{ $pro_atts->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                                <div class="plus-minus">
                                    <span class="minus">
                                        <i class="fa-solid fa-square-minus"></i>
                                    </span>

                                    <input type="number" max="10000" min="1" class="count" name="quantity"
                                        value="1" id="quantity-select" step="1">
                                    <span class="plus">
                                        <i class="fa-solid fa-square-plus"></i>
                                    </span>
                                    @if($product_detail->stock > 0 || $product_detail->back_order == 1)
                                        
                                            <div class="add-cart-btn">
                                                <button class="gold_btn" >Add To Cart</button>
                                            </div>
                                      
                                    @endif
                                    <?php
                                        $wishlist = App\wishlists::where('product_id', $product_detail->id)->where('user_id', Auth::user()->id)->first();
                                    
                                    ?>
                                        @if ($wishlist != '')
                                    <a href="{{ route('customer.wishlist.list') }}" data-link="" class="quick-heart details-quick-heart" data-tooltip="View Wishlist" data-id="{{ $product_detail->id }}"><i class="fa fa-heart"></i></a>
                                        @else
                                    <a href="{{ route('wishlist.add', ['id' => $product_detail->id]) }}" data-link="" class="quick-heart details-quick-heart" data-tooltip="Add To Wishlist" data-id="{{ $product_detail->id }}"><i class="fa-regular fa-heart"></i></a>
                                        @endif
                                </div>
                                <div class="detail-tags mb-4">
                                    <p class="paragraph">SKU : <span id="sku">{{ $product_detail->sku }}</span>
                                    </p>
                                    <p class="paragraph">CATEGORIES : <span id="categories">
                                            {{ implode(', ', array_unique($cat_listing)) }}
                                        </span>
                                    </p>
                                    <p class="paragraph">TAGS : <span id="product_tags">{{ $product_detail->tags }}</span>
                                    </p>
                                </div>
                                <p class="paragraph mb-3">Guaranteed Safe Checkout</p>
                                <div class="payment_method">
                                    <div id="paypal-button-container"></div>
                                </div>
                                <hr />
                                <div class="shareBtn">
                                    <h3 class="black_heading40">SHARE THIS PRODUCT</h3>
                                    <div class="mt-5 sharethis-inline-share-buttons">
                                        @php
                                            $shareUrl = route('shopDetail', ['slug' => $product_detail->slug ]); // Replace with the actual post URL
                                            $facebookShareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . $shareUrl;
                                            $twitterShareUrl = '' . $shareUrl;
                                            $pinterestUrl = 'https://www.pinterest.com/pin-builder/?description=' . $shareUrl;
                                            $ggoglePlus = 'https://plus.google.com/share?url=' . $shareUrl;
                                            $linkdin = 'https://www.linkedin.com/uas/login?session_redirect=' . $shareUrl;
                                        @endphp
                                        <ul class="social-icons">
                                            <li><a class="facebook social-icon" href="#"
                                                    onclick="javascript: window.open('{{ $facebookShareUrl }}');  return false;"
                                                    title="Facebook" target="_blank"><i
                                                        class="fa-brands fa-facebook"></i></a></li>
                                            <li><a class="twitter social-icon" href="#" title="Twitter"
                                                    onclick="javascript: window.open('{{ $twitterShareUrl }}');  return false;"
                                                    target="_blank"><i class="fa-brands fa-twitter"></i></a></li>
                                            <li><a class="pinterest social-icon" href="#"
                                                    onclick="javascript: window.open('{{ $pinterestUrl }}');  return false;"
                                                    title="Pinterest" target="_blank"><i
                                                        class="fa-brands fa-pinterest"></i></a></li>
                                            <li><a class="gplus social-icon" href="#"
                                                    onclick="javascript: window.open('{{ $ggoglePlus }}');  return false;"
                                                    title="Google +" target="_blank"><i
                                                        class="fa-brands fa-google-plus"></i></a></li>
                                            <li><a class="linkedin social-icon" href="#"
                                                    onclick="javascript: window.open('{{ $linkdin }}');  return false;"
                                                    title="LinkedIn" target="_blank"><i
                                                        class="fa-brands fa-linkedin"></i></a></li>
                                        </ul>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form class='prdForm' action="{{route('prdCheckout')}}" method="post">
                        <input name="payment_status" hidden/>
                        <input name="payment_id" hidden/>
                        <input name="payer_id" hidden/>
                        <input name="shipping_tax" hidden/>
                        <input name="payment_method" hidden/>
                        <input name="productAttr" class="productAttr" hidden/>
                        <input type="hidden" class="prdPrice" name="prdPrice" value="{!!$product_detail->regular_price > 0 ? $product_detail->regular_price : $product_detail->getMinPrice() !!}"/> 
                        <input type="number"  class="prdCount" name="prdCount"  value="1"  step="1" hidden>
                        <input type="hidden" class="finalPrice" name="finalPrice" value=""/>
                        
                     
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section class="relatedProducts_sec">
        <div class="container">
            <p class="black_heading40 mb-4">These go well together</p>
            <div class="row">
                
                @foreach ($shop as $shops)
                    <?php
                    $wishlist = App\wishlists::where('product_id', $shops->id)
                        ->where('user_id', Auth::user()->id)
                        ->first();
                    
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="featureProduct">
                            <div class="featureProduct_image">
                                <img src="{{ asset($shops->image) }}" alt="{{ $shops->product_title }}"
                                    class="img-fluid">
                                @php
                                    $spanText = '';
                                    if ($value->discount_buy_qty > 0 && $value->discount_get_qty > 0) {
                                        $spanText = `Buy ${$value->discount_buy_qty} get ${$value->discount_get_qty} free`;
                                    } elseif ($value->new_product == 1) {
                                        $spanText = 'NEW';
                                    }
                                    
                                @endphp
                                @if ($spanText != '')
                                    <span class="lastProduct">{{ $spanText }}</span>
                                @endif
                                <!-- <span class="lastProduct">Last One!</span> -->
                                @if ($wishlist != '')
                                    <a href="{{ route('customer.wishlist.list') }}" data-link="" class="quick-heart"
                                        data-tooltip="Add To Wishlist" data-id="{{ $shops->id }}"><i
                                            class="fa fa-heart"></i></a>
                                @else
                                    <a href="{{ route('wishlist.add', ['id' => $shops->id]) }}" data-link=""
                                        class="quick-heart" data-tooltip="View Wishlist"
                                        data-id="{{ $shops->id }}"><i class="fa-regular fa-heart"></i></a>
                                @endif
                                <a href="#"
                                    data-link="{{ route('shopDetail', ['slug' => $shops->slug]) }}"
                                    data-tooltip="Quick View" class="quick-view" data-id="{{ $shops->id }}"><i
                                        class="fa-regular fa-eye"></i></a>
                                <a href="{{ route('shopDetail', ['slug' => $shops->slug ]) }}"
                                    class="shopNow_btn gold_btn">Shop Now</a>
                            </div>
                            <div class="featureProduct_detail">
                                <div class="best-wrapper">
                                    <span>{{ $shops->best_seller }}</span>
                                </div>
                                <p class="mb-3">{!! $shops->product_title !!}</p>
                                <p>{!! $shops->getMinPrice() !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @endsection @section('css')
    <style>
        .social-icons {
            float: left;
            margin: 0;
            padding: 0;
        }

        .social-icons li {
            float: left;
            line-height: 30px;
            list-style: none;
            margin-right: 10px;
        }

        .social-icons li {
            display: inline-block;
            margin: 0 15px 5px 0;
            position: relative;
            vertical-align: middle;
        }

        .social-icon {
            background: #fff;
            border: 1px solid #ebebeb;
            color: #999;
            display: inline-block;
            font-size: 18px;
            height: 40px;
            line-height: 38px;
            text-align: center;
            width: 40px;
            border-radius: 100%;
        }

        .social-icon.facebook:hover {
            background: #3b579d;
            border: 1px solid #3b579d;
        }

        .social-icon:hover {
            color: #fff;
        }

        .social-icon.twitter:hover {
            background: #3acaff;
            border: 1px solid #3acaff;
        }

        .social-icon.pinterest:hover {
            background: #cb2027;
            border: 1px solid #cb2027;
        }

        .social-icon.gplus:hover {
            background: #d11717;
            border: 1px solid #d11717;
        }

        .social-icon.linkedin:hover {
            background: #0097bd;
            border: 1px solid #0097bd;
        }
        .payment_method {
            width: 40%;
        }
        .prodcutDetail_image video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .has_discount_category {
            padding: 3px 30px !important;
        }
    </style>
@endsection
@section('js')
    <!--<script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=63cec720d7982a0019bfb43d&product=inline-share-buttons&source=platform"
        async="async"></script>-->
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID') }}&components=buttons&disable-funding=paylater,credit,card"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var sync1 = $("#sync1");
            var sync2 = $("#sync2");
            var slidesPerPage = 4; //globaly define number of elements per page
            var syncedSecondary = true;

            sync1.owlCarousel({
                items: 1,
                slideSpeed: 2000,
                nav: true,
                autoplay: false,
                dots: false,
                loop: false,
                responsiveRefreshRate: 200,
                navText: [
                    '<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>',
                    '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'
                ],
            }).on('changed.owl.carousel', syncPosition);

            sync2
                .on('initialized.owl.carousel', function() {
                    sync2.find(".owl-item").eq(0).addClass("current");
                })
                .owlCarousel({
                    items: slidesPerPage,
                    dots: false,
                    nav: false,
                    loop: false,
                    smartSpeed: 200,
                    slideSpeed: 500,
                    slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                    responsiveRefreshRate: 100
                }).on('changed.owl.carousel', syncPosition2);

            function syncPosition(el) {
                var count = el.item.count - 1;
                var current = el.item.index;
                sync2.data('owl.carousel').to(current, 100, true);
                sync2
                    .find(".owl-item")
                    .removeClass("current")
                    .eq(current)
                    .addClass("current");
                // var onscreen = sync2.find('.owl-item.active').length - 1;
                // var start = sync2.find('.owl-item.active').first().index();
                // var end = sync2.find('.owl-item.active').last().index();

                // if (current > end) {
                //     sync2.data('owl.carousel').to(current, 100, true);
                // }
                // if (current < start) {
                //     sync2.data('owl.carousel').to(current - onscreen, 100, true);
                // }
            }

            function syncPosition2(el) {
                if (syncedSecondary) {
                    var number = el.item.index;
                    // sync1.data('owl.carousel').to(number, 100, true);
                }
            }

            sync2.on("click", ".owl-item", function(e) {
                e.preventDefault();
                var number = $(this).index();
                sync1.data('owl.carousel').to(number, 300, true);
            });
        });

        $('.variation-select').change(function() {
            var stock = $(this).find(":selected").attr('data-stock');
            var image = $(this).find(":selected").attr('data-image');
            var select = $(this).find(":selected").attr('data-select');
            var price = $(this).find(":selected").attr('data-price');
            var regular_price = $(this).find(":selected").attr('data-regular_price');
            var actual_price = 0;
            var attId = $(this).find(":selected").attr('data-id');
            $('.productAttr').val('attId');
            if (price == 0) {
                actual_price = regular_price;
                $('.prdPrice').val(actual_price);
            } else {
                actual_price = price;
                 $('.prdPrice').val(actual_price);
            }
            $('#price-show strong').text('$');
            $('#price-show span').text(actual_price);
            if (image != '') {
                $('#sync1').trigger('to.owl.carousel', select);
            }
            if (stock != '') {
                if(stock == 0){
                    $('.stock-info').html('<p class="stock-danger"><i class="fa-solid fa-circle-check"></i> ' + stock + ' in stock</p>');
                }else{
                    $('.stock-info').html('<p><i class="fa-solid fa-circle-check"></i> ' + stock + ' in stock</p>');
                }
            }
            // $('#sync2').trigger('to.owl.carousel', select);
        })
    </script>
    <script>


    var shipping_amount = 0;
    var totalAmountPrice = 0
    
    var paypalActions;
    paypal.Buttons({
        style: {
            label: 'checkout',
            size:  'responsive',  
            shape: 'rect',    
            color: 'gold'   
        },
        env: "{{env('PAYPAL_PRODUCTION')}}", //production
        createOrder: function(data, actions) {
            // Set up the transaction
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: parseFloat(totalAmountPrice) + shipping_amount,
                    }
                }]
            });
        },
        onClick: function(data, actions) {
            
            var totalAmount = $('.prdCount').val() * $('.prdPrice').val().replace(/\$/g, '');;
            // alert(totalAmount);
            $('.finalPrice').val(totalAmount);
            
            totalAmountPrice = $('.finalPrice').val();
            // alert(totalAmountPrice);
            
            if (totalAmountPrice == 0 ) {
                $.toast({
                    heading: 'Alert!',
                    position: 'bottom-right',
                    text: 'Amount is Not Valid',
                    loaderBg: '#ff6849',
                    icon: 'error',
                    hideAfter: 5000,
                    stack: 6
                });

                return actions.reject();
            } else {
                return actions.resolve();

            }

        },

        onApprove: function (data, actions) {
              return actions.order.capture()
                        .then(function (details) {
                            if(details['status'] == "COMPLETED")
                            {
                               toastr.success('Payment Authorized! Thank You For Booking')
                                $('input[name="payment_status"]').val('Completed');
                                $('input[name="payment_id"]').val(data.orderID);
                                $('input[name="payer_id"]').val(data.payerID);
                                
                                $('input[name="shipping_tax"]').val(shipping_amount);
                                $('input[name="payment_method"]').val('paypal');
                                $('#prdForm').submit();
                            }
                            else{
                                toastr.error('Something went wrong');
                            }

                        });
            
        }
    }).render('#paypal-button-container');
    </script>
@endsection
