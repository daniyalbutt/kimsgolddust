@extends('layouts.main')
@section('title', 'Search Result For '.$keyword)

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

<section class="inner_banner">
  <div class="container">
    <div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">
      <h1>Search Result For {{$keyword}}</h1>
      <p>Home / Search Result For {{$keyword}}</p>
    </div>
  </div>
</section>

<section class="shop_sec1">
    <div class="container">
        <div class="shop_wrap">
            <div class="shop_right shop_right_100">
                <div class="featureProducts_wrap">
                    <div class="shop_Sec1_heading">
                        <p class="black_heading30 mb-4">Search Result For {{ $keyword }} ({{ count($cat) }})</p>
                        <span class="filter_btn">
                            <i class="fa-solid fa-sliders"></i>
                        </span>
                    </div>
                    <div class="row">
                        @foreach($cat as $key => $value)
                        <?php
                        $wishlist = App\wishlists::where('product_id', $value->id)
                            ->where('user_id', Auth::user()->id)
                            ->first();
                        $att_model = \App\ProductAttribute::groupBy('attribute_id')->where('product_id', $value->id)->get();
                        ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="featureProduct">
                                <div class="featureProduct_image">
                                    <img src="{{ asset($value->image) }}" alt="{{ $value->product_title }}" class="img-fluid">
                                    @if($value->new_product == 1)
                                    <span class="lastProduct">NEW</span>
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
                                        data-link="{{ route('shopDetail', ['slug' => $value->slug ]) }}"
                                        data-tooltip="Quick View" class="quick-view" data-id="{{ $value->id }}"><i
                                            class="fa-regular fa-eye"></i></a>
                                    <a href="{{ route('shopDetail', ['slug' => $value->slug ]) }}"
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
        </div>
    </div>
</section>

<!--Footer Content Start-->
<!-- ============================================================== -->
<!-- BODY END HERE -->
<!-- ============================================================== -->
@endsection
@section('css')
<style></style>
@endsection
@section('js')
<script type="text/javascript"></script>
@endsection