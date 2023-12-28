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


@section('additional_seo')
{!! $page->additional_seo !!}
@endsection


@section('content')
<!--<section class="inner_banner" style="background: {{ $page->banner_color  }}">-->
<!--  	<div class="container">-->
<!--    	<div class="innerBanner_content" data-aos="fade-down" data-aos-duration="1200">-->
<!--      		<h1>{{ $page->name }}</h1>-->
<!--      		<p>Home / {{ $page->name }}</p>-->
<!--    	</div>-->
<!--  	</div>-->
<!--</section>-->

<section class="customOrder_sec1">
    <div class="container">
        <div class="customOrder_sec1_wrap custom-order-top">
        	{!! $page->content !!}
        </div>
    </div>
</section>
<section class="customOrder_sec2 custom-order-sec-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h4 class="orange_heading">{{ $section[0]->value }}</h4>
            </div>
            <div class="col-md-6">
                <img src="{{ asset($section[4]->value) }}" alt="" class="img-fluid car_image">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="custom-order-box">
                    {!! $section[5]->value !!}
                    <img src="{{ asset($section[6]->value) }}" alt="{{ $section[6]->alter_tag }}" class="img-fluid">
                    {!! $section[7]->value !!}
                    {!! $section[1]->value !!}
                    <img src="{{ asset($section[2]->value) }}" alt="{{ $section[2]->alter_tag }}" class="img-fluid">
                    <!--{!! $section[3]->value !!}-->
                    
                </div>
            </div>
        </div>
    </div>
</section>
<section class="customOrder_sec3 custom-order-sec3">
    <div class="container">
        <div class="custom_clientReviewBox">
            <div class="client_logo mb-4">
                <img src="{{ asset($section[8]->value) }}" alt="" class="img-fluid">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="custom-order-box">
                        {!! $section[12]->value !!}
                        <div class="customClient_info custom-client-info">
                            <img src="{{ asset($section[9]->value) }}" alt="{{ $section[9]->alter_tag }}" class="img-fluid">
                            <div class="custom-client-info-left">
                                {!! $section[10]->value !!}
                                <div class="client_rating">
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <img src="{{ asset($section[13]->value) }}" alt="{{ $section[13]->alter_tag }}" class="img-fluid">
                        {!! $section[14]->value !!}
                        <div class="customClient_info custom-client-info">
                            <img src="{{ asset($section[15]->value) }}" alt="{{ $section[15]->alter_tag }}" class="img-fluid">
                            <div class="custom-client-info-left">
                                {!! $section[16]->value !!}
                                <div class="client_rating">
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="customOrder_sec3 custom-order-sec4">
    <div class="container">
        <div class="custom_clientReviewBox">
            <div class="client_logo mb-4">
                <h4>Grand Sport Registry</h4>
                <img src="{{ asset($section[17]->value) }}" alt="{{ $section[17]->alter_tag }}" class="img-fluid">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="custom-order-box">
                        {!! $section[23]->value !!}
                        {!! $section[24]->value !!}
                        <div class="custom_testImages">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                                    <div class="custom_testImg">
                                        <img src="{{ asset($section[20]->value) }}" alt="{{ $section[20]->alter_tag }}" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                                    <div class="custom_testImg">
                                        <img src="{{ asset($section[21]->value) }}" alt="{{ $section[21]->alter_tag }}" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                                    <div class="custom_testImg">
                                        <img src="{{ asset($section[22]->value) }}" alt="{{ $section[22]->alter_tag }}" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="customClient_info custom-client-info">
                            <img src="{{ asset($section[18]->value) }}" alt="" class="img-fluid">
                            <div class="custom-client-info-left">
                                {!! $section[19]->value !!}
                                <div class="client_rating">
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="customOrder_sec5 custom-order_sec5">
    <div class="container">
        <div class="custom_clientReviewBox mb-5">
            <div class="client_logo">
                <h4>Car Art Work</h5>
                <img src="{{ asset($section[25]->value) }}" alt="{{ $section[25]->alter_tag }}" class="img-fluid">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="custom-order-box">
                        {!! $section[27]->value !!}
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <div class="customClient_info custom-client-info" style="align-items: end;height: 100%;">
                                    <img src="{{ asset($section[25]->value) }}" alt="" class="img-fluid" style="width: 65px;height: 120px;object-fit: contain;border-radius: 0;">
                                    <div class="custom-client-info-left" style="margin-bottom: 30px;">
                                        {!! $section[32]->value !!}
                                        <div class="client_rating">
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                            <i class="fa-sharp fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <img src="{{ asset($section[26]->value) }}" alt="{{ $section[26]->alter_tag }}" class="img-fluid">
                            </div>
                            <div class="col-md-2">
                                <img src="{{ asset($section[33]->value) }}" alt="{{ $section[33]->alter_tag }}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="customOrder_sec6">
    <div class="container">
        <div class="customOrder_sec6_wrap" style="background-image: url({{ asset($section[28]->value) }});">
            <h4 class="black_heading mb-4">{{ $section[29]->value }}</h4>
            <div class="customSec6_btn">
                <a href="{{ $section[31]->value }}" class="gold_btn w-100">{{ $section[30]->value }}</a>
            </div>
        </div>
    </div>
</section>

@endsection
@section('css')
<style>
    @if($page->left_image != null)
    .inner_banner:before{
        background: url({{ asset($page->left_image) }});
    }
    @endif
    @if($page->right_image != null)
    .inner_banner:after{
        background: url({{ asset($page->right_image) }});
    }
    @endif
</style>
@endsection

@section('js')
<script type="text/javascript"></script>
@endsection